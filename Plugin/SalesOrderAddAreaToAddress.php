<?php

declare(strict_types=1);

namespace Elogic\AddressArea\Plugin;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\SalesGraphQl\Model\Order\OrderAddress;

class SalesOrderAddAreaToAddress
{
    /**
     * @var ArrayManager
     */
    private ArrayManager $arrayManager;

    /**
     * Custom attribute code
     * @var string
     */
    private string $attrCode;

    public function __construct(
        ArrayManager $arrayManager,
        string $attrCode = null
    ) {
        $this->arrayManager = $arrayManager;
        $this->attrCode = is_null($attrCode) ? "area" : $attrCode;
    }

    /**
     * @param OrderAddress $subject
     * @param array $result
     * @param OrderInterface $order
     * @return array
     */
    public function afterGetOrderBillingAddress(
        OrderAddress $subject,
        array $result,
        OrderInterface $order
    ): array {
        return $this->addAttrValue(
            $result,
            $order->getBillingAddress()
        );
    }

    /**
     * @param OrderAddress $subject
     * @param array $result
     * @param OrderInterface $order
     * @return array
     */
    public function afterGetOrderShippingAddress(
        OrderAddress $subject,
        array $result,
        OrderInterface $order
    ): array {
        return $this->addAttrValue(
            $result,
            $order->getShippingAddress()
        );
    }

    /**
     * Adding custom attribute to result array
     *
     * @param array $result
     * @param OrderAddressInterface|null $address
     * @return array
     */
    private function addAttrValue(array $result, ?OrderAddressInterface $address): array
    {
        if (!is_null($address) && !isset($result[$this->attrCode])) {
            $result[$this->attrCode] = $address->getData($this->attrCode);
        }

        return $result;
    }
}
