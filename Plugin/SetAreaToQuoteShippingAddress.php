<?php

declare(strict_types=1);

namespace Elogic\AddressArea\Plugin;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\ShippingAddressManagement;

class SetAreaToQuoteShippingAddress extends SetAreaToQuoteAddress
{
    public function beforeAssign(
        ShippingAddressManagement $subject,
        $cartId,
        AddressInterface $address
    ) {
        $this->addArea($address);
    }
}
