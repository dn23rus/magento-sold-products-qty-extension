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
    $setup->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'sold_qty_enable', 'used_in_product_listing', 1);
    $setup->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'sold_qty_period', 'used_in_product_listing', 1);
    $setup->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'sold_qty_threshold', 'used_in_product_listing', 1);
    $setup->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'sold_qty_extra', 'used_in_product_listing', 1);
} catch (Exception $e) {
    Mage::logException($e);
}
$installer->endSetup();
