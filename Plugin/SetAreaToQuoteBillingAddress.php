<?php

declare(strict_types=1);

namespace Elogic\AddressArea\Plugin;

use Magento\Quote\Model\BillingAddressManagement;

class SetAreaToQuoteBillingAddress extends SetAreaToQuoteAddress
{
    public function beforeAssign(
        BillingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address,
        bool $useForShipping = false
    ) {
        $this->addArea($address);
    }
}
