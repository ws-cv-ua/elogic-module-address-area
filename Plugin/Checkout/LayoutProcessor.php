<?php

declare(strict_types=1);

namespace Elogic\AddressArea\Plugin\Checkout;

use Magento\Framework\Stdlib\ArrayManager;

/**
 * Add to checkout layout address form custom area attribute
 */
class LayoutProcessor
{
    private ArrayManager $_arrayManager;

    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->_arrayManager = $arrayManager;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ): array {
        // Add to shipping form
        $type = 'shipping';
        $jsLayout = $this->_arrayManager->set(
            "components/checkout/children/steps/children/" .
            "shipping-step/children/shippingAddress/children/shipping-address-fieldset/children/area",
            $jsLayout,
            [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'config' => [
                    'customScope' => $type . 'Address.custom_attributes',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input',
                    'options' => [],
                    'id' => 'area-' . $type
                ],
                'dataScope' => $type . 'Address.custom_attributes.area',
                'label' => __("Area"),
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => [],
                'sortOrder' => 250,
                'id' => 'area-' . $type
            ]
        );

        // billing
        $paymentListPath = 'components/checkout/children/steps/children/billing-step/children/payment/children/payments-list';
        if ($this->_arrayManager->exists($paymentListPath, $jsLayout)) {
            $paymentForms = $this->_arrayManager->get("{$paymentListPath}/children", $jsLayout, []);

            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {
                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);

                $formKey = "$paymentListPath/children/{$paymentMethodCode}-form";
                if (!$this->_arrayManager->exists($formKey, $jsLayout)) {
                    continue;
                }

                $billingFieldsPath = "$formKey/children/form-fields/children";
                $billingFields = $this->_arrayManager->get($billingFieldsPath, $jsLayout);

                if (!isset($billingFields['area'])) {
                    $scope = "billingAddress{$paymentMethodCode}.custom_attributes";
                    $billingFields['area'] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'config' => [
                            'customScope' => $scope,
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input',
                        ],
                        'dataScope' => "{$scope}.area",
                        'label' => __("Area"),
                        'provider' => 'checkoutProvider',
                        'visible' => true,
                        'sortOrder' => 250,
                    ];
                    $jsLayout = $this->_arrayManager->set($billingFieldsPath, $jsLayout, $billingFields);
                }
            }
        }

        return $jsLayout;
    }
}
