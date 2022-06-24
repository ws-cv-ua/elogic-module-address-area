var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Elogic_AddressArea/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/set-billing-address': {
                'Elogic_AddressArea/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-shipping-address': {
                'Elogic_AddressArea/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Elogic_AddressArea/js/action/set-billing-address-mixin': true
            },
        }
    }
};
