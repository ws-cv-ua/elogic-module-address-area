<?php

declare(strict_types=1);

namespace Elogic\AddressArea\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class SetAreaForOrderAddresses
{
    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $result
     * @param int $id
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $result, $id): OrderInterface
    {
        $shippingAddress = $result->getShippingAddress();
        $billingAddress = $result->getBillingAddress();

        foreach ([$shippingAddress, $billingAddress] as $address) {
            try {
                $area = $address->getArea();
                if (!empty($area)) {
                    /** @var \Magento\Sales\Api\Data\OrderAddressInterface $address */
                    $extensionAttributes = $address->getExtensionAttributes();
                    $extensionAttributes->setData('area', $area);
                    $address->setExtensionAttributes($extensionAttributes);
                }
            } catch (\Exception $e) {
                // TODO: add logs
            }

        }

        return $result;
    }
}
