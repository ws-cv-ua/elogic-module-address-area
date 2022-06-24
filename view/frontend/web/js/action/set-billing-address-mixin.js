define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setBillingAddressAction) {
        return wrapper.wrap(setBillingAddressAction, function (originalAction, messageContainer) {

            var billingAddress = quote.billingAddress();

            if (typeof billingAddress != "undefined" && billingAddress != null) {

                if (typeof billingAddress['extension_attributes'] == "undefined") {
                    billingAddress['extension_attributes'] = {};
                }

                if (typeof billingAddress.customAttributes != "undefined") {
                    $.each(billingAddress.customAttributes, function (i, obj) {
                        let k = obj.attribute_code;
                        if (typeof billingAddress['extension_attributes'][k] == "undefined") {
                            billingAddress['extension_attributes'][k] = obj.value;
                        }
                    });
                }
            }

            return originalAction(messageContainer);
        });
    };
});
