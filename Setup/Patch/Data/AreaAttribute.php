<?php

declare(strict_types=1);

namespace Elogic\AddressArea\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Psr\Log\LoggerInterface;

class AreaAttribute implements DataPatchInterface, SchemaPatchInterface
{
    private ModuleDataSetupInterface $_moduleDataSetup;
    private CustomerSetupFactory $_customerSetupFactory;
    private AttributeSetFactory $_attributeSetFactory;
    private LoggerInterface $_logger;
    private string $_attrCode;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        LoggerInterface $logger,
        string $attrCode = null
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_customerSetupFactory = $customerSetupFactory;
        $this->_attributeSetFactory = $attributeSetFactory;
        $this->_logger = $logger;
        $this->_attrCode = $attrCode ?: 'area';
    }

    public function apply()
    {
        $this->_moduleDataSetup->getConnection()->startSetup();
        $customerSetup = $this->_customerSetupFactory->create(['setup' => $this->_moduleDataSetup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        $attributeSet = $this->_attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        try {
            $customerSetup->addAttribute('customer_address', $this->_attrCode, [
                'label' => 'Area',
                'system' => 0,
                'position' => 900,
                'sort_order' => 900,
                'visible' => true,
                'type' => 'varchar',
                'input' => 'text',
                'required' => false,
            ]);
        } catch (LocalizedException|\Zend_Validate_Exception $e) {
            $this->_logger->critical("Can't create attribute {$this->_attrCode}");
        }

        $customerSetup->getEavConfig()->getAttribute('customer_address', $this->_attrCode)
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => [
                    'adminhtml_customer_address',
                    'customer_address_edit',
                    'customer_register_address'
                ]
            ])->save();

        $this->_moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
