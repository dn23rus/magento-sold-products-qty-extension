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
 * Sold products resource
 *
 * @category   Oggetto
 * @package    Oggetto_SoldProducts
 * @subpackage Model
 * @author     Dmitry Buryak <b.dmitry@oggettoweb.com>
 */
class Oggetto_SoldProducts_Model_Resource_SoldProducts extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * @var int
     */
    protected $_periodMultiplier = 86400;

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_setResource(Mage_Core_Model_Resource::DEFAULT_READ_RESOURCE);
    }

    /**
     * Get sold products qty for the period.
     *
     * @param int $productId product id
     * @param int $period    period
     * @return string|bool
     */
    public function getSoldProductsQty($productId, $period)
    {
        $period = $period * $this->_periodMultiplier;
        $currentDate = $this->formatDate(time());
        $select = $this->getReadConnection()->select()
            ->from(
                array('items' => $this->getTable('oggetto_soldproducts/data')),
                array('qty' => new Zend_Db_Expr('SUM(items.qty_ordered)'))
            )
            ->where('product_id = ?', $productId, Zend_Db::INT_TYPE)
            ->where("(TO_SECONDS('{$currentDate}') - TO_SECONDS(items.date_time)) <= {$period}")
            ;
        return $this->getReadConnection()->fetchOne($select);
    }

    /**
     * Get sold product qty for collection
     *
     * @param array $productIds product ids
     * @return array
     */
    public function getSoldQtyForCollection(array $productIds)
    {
        $defaultPeriod = (int) Mage::getStoreConfig('sold_products/default_settings/period');
        $periodAttribute = Mage::getSingleton('eav/config')->getAttribute(
            Mage_Catalog_Model_Product::ENTITY, 'sold_qty_period'
        );
        $periodTable = $periodAttribute->getBackend()->getTable();
        $attributeId = $periodAttribute->getAttributeId();

        $currentDate = $this->formatDate(time());
        $select = $this->getReadConnection()->select()
            ->from(
                array('items' => $this->getTable('oggetto_soldproducts/data')),
                array(
                    'product_id' => 'items.product_id',
                    'qty' => new Zend_Db_Expr('SUM(items.qty_ordered)'),
                    'items.date_time',
                )
            )
            ->joinLeft(
                array('period_attr_tbl' => $periodTable),
                'items.product_id = period_attr_tbl.entity_id AND period_attr_tbl.attribute_id = ' . $attributeId,
                array('period' => new Zend_Db_Expr("IFNULL(period_attr_tbl.value, {$defaultPeriod})"))
            )
            ->where('product_id IN (?)', $productIds)
            ->group('product_id')
            ->where("(TO_SECONDS('{$currentDate}') - TO_SECONDS(items.date_time)) <=" .
                "IFNULL(period_attr_tbl.value, {$defaultPeriod}) * {$this->_periodMultiplier}");

        return $this->getReadConnection()->fetchAll($select);
    }

    /**
     * Log sold products
     *
     * @param array $data data
     * @return Oggetto_SoldProducts_Model_Resource_SoldProducts
     */
    public function logSoldProductsQty(array $data)
    {
        $this->_getWriteAdapter()->insertMultiple($this->getTable('oggetto_soldproducts/data'), $data);
        return $this;
    }

    /**
     * Rebuild order items log
     *
     * @return Oggetto_SoldProducts_Model_Resource_SoldProducts
     * @throws Exception
     */
    public function rebuildOrderItemsLog()
    {
        $query = $this->_getWriteAdapter()->insertFromSelect(
            $this->_getWriteAdapter()->select()
                ->from(
                    array('sfoi' => $this->getTable('sales/order_item')),
                    array(
                        'product_id'    => 'sfoi.product_id',
                        'date_time'     => 'sfoi.created_at',
                        'qty_ordered'   => 'sfoi.qty_ordered',
                    )
                ),
            $this->getTable('oggetto_soldproducts/data'),
            array('product_id', 'date_time', 'qty_ordered')
        );
        $this->_getWriteAdapter()->getTransactionLevel();
        try {
            $this->_getWriteAdapter()->truncateTable($this->getTable('oggetto_soldproducts/data'));
            $this->_getWriteAdapter()->query($query);
        } catch (Exception $e) {
            $this->_getWriteAdapter()->rollBack();
            throw $e;
        }
        $this->_getWriteAdapter()->commit();
        return $this;
    }
}
