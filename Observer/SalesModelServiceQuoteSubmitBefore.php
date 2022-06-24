<?php

declare(strict_types=1);

namespace Elogic\AddressArea\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Set area to sales addresses
 */
class SalesModelServiceQuoteSubmitBefore implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        $shippingAddressData = $quote->getShippingAddress()->getData();
        if (isset($shippingAddressData['area'])) {
            $order->getShippingAddress()->setArea($shippingAddressData['area']);
        }

        $billingAddressData = $quote->getBillingAddress()->getData();
        if (isset($billingAddressData['area'])) {
            $order->getBillingAddress()->setArea($billingAddressData['area']);
        } else {
            $extAttrs = $quote->getBillingAddress()->getExtensionAttributes();
            if (!empty($extAttrs)) {
                try {
                    $area = $extAttrs->getArea();
                    if ($area) {
                        $order->getBillingAddress()->setArea($area);
                    }
                } catch (\Exception $e) {
                    // TODO: add logs
                }
            }
        }

        return $this;
    }
}
