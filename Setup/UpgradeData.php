<?php

namespace Space48\PreSell\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Space48\DBUpdates\Model\Eav\Attribute;
use Magento\Eav\Setup\EavSetupFactory;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;


    /**
     * UpgradeData constructor.
     *
     * @param AttributeRepositoryInterface $attributeRepository
     *
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        EavSetupFactory $eavSetupFactory
    )
    {
        $this->attributeRepository = $attributeRepository;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {


            $attribute = new Attribute($this->attributeRepository, Product::ENTITY);
            $attribute->delete("pre_sell");
            $attribute->delete("pre_sell_qty");

            /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->addAttribute(
                Product::ENTITY,
                'pre_sell',
                [
                    'type'                    => 'int',
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => 'Pre-sell Available?',
                    'input'                   => 'select',
                    'class'                   => '',
                    'source'                  => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'global'                  => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => true,
                    'default'                 => null,
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => true,
                    'unique'                  => false,
                    'group'                   => 'Product Details',
                    'apply_to'                => ''
                ]
            );

            $eavSetup->addAttribute(
                Product::ENTITY,
                'pre_sell_qty',
                [
                    'type'                    => 'int',
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => 'Pre-sell Quantity',
                    'input'                   => 'text',
                    'class'                   => '',
                    'source'                  => '',
                    'global'                  => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => true,
                    'default'                 => null,
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => true,
                    'unique'                  => false,
                    'group'                   => 'Product Details',
                    'apply_to'                => ''
                ]
            );
        }
    }
}
