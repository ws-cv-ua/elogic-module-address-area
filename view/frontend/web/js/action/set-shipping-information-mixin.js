define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            if (typeof shippingAddress['extension_attributes'] == "undefined") {
                shippingAddress['extension_attributes'] = {};
            }

            if (typeof shippingAddress.customAttributes != "undefined"){
                $.each(shippingAddress.customAttributes, function (index, obj) {
                    let k = obj.attribute_code;
                    if (typeof shippingAddress['extension_attributes'][k] == "undefined") {
                        shippingAddress['extension_attributes'][k] = obj.value;
                    }
                });
            }
            return originalAction();
        });
    };
});
