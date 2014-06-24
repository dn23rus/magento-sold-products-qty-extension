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
    $table = $installer->getConnection()->newTable($this->getTable('oggetto_soldproducts/data'));
    $table
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Identity')
        ->addColumn('date_time', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable'  => false
        ), 'DateTime')
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
            'unsigned'  => true,
            'nullable'  => false,
        ), 'Product Id')
        ->addColumn('qty_ordered', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => 0,
        ), 'Qty Ordered')
        ;
    $table->addIndex($this->getIdxName('oggetto_soldproducts/data', 'product_id'), 'product_id');
    $table->addIndex($this->getIdxName('oggetto_soldproducts/data', 'date_time'), 'date_time');
    $installer->getConnection()->createTable($table);
} catch (Exception $e) {
    Mage::logException($e);
}
$installer->endSetup();
