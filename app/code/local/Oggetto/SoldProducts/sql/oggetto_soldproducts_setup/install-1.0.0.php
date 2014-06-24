<?php
/**
 * Oggetto Web extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto SoldProducts module to newer versions in the future.
 * If you wish to customize the Oggetto SoldProducts module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_SoldProducts
 * @copyright  Copyright (C) 2014 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @var $this Mage_Core_Model_Resource_Setup
 */

$installer = $this;
$installer->startSetup();

try {
    $setup = new Mage_Catalog_Model_Resource_Setup(Mage_Core_Model_Resource::DEFAULT_SETUP_RESOURCE);
    $attributes = array(
        'sold_qty_enable' => array(
            'label'         => 'Enable',
            'type'          => 'int',
            'input'         => 'boolean',
            'source'        => 'eav/entity_attribute_source_boolean',
            'sort_order'    => 10,
        ),
        'sold_qty_period' => array(
            'label'         => 'Period',
            'type'          => 'int',
            'input'         => 'text',
            'sort_order'    => 20,
        ),
        'sold_qty_threshold' => array(
            'label'         => 'Show when QTY more',
            'type'          => 'int',
            'input'         => 'text',
            'sort_order'    => 30,
        ),
        'sold_qty_extra' => array(
            'label'         => 'Extra QTY',
            'type'          => 'int',
            'input'         => 'text',
            'sort_order'    => 30,
        ),
    );

    foreach ($attributes as $attribute => $specification) {
        $specification['group'] = 'Sold Products QTY';
        $specification['user_defined'] = false;
        $specification['required'] = false;
        $specification['apply_to'] = 'simple';
        $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attribute, $specification);
    }
} catch (Exception $e) {
    Mage::logException($e);
}
$installer->endSetup();
