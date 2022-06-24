<?php

declare(strict_types=1);

namespace Elogic\AddressArea\Plugin;

use Magento\Quote\Api\Data\AddressInterface;

class SetAreaToQuoteAddress
{
    /**
     * If isset area in ext attributes then set it into address entity
     * @param AddressInterface $address
     * @return void
     */
    protected function addArea(AddressInterface $address)
    {
        $extAttr = $address->getExtensionAttributes();
        if (!empty($extAttr)) {
            try {
                $area = $extAttr->getArea();
                if (!empty($area)) {
                    $address->setArea($area);
                }
            } catch (\Exception $e) {
                // TODO: add logs
            }
        }
    }
}
