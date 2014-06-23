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
        $period = (int) $period;
        $currentDate = $this->formatDate(time());
        $select = $this->getReadConnection()->select()
            ->from(
                array('order_item' => $this->getTable('sales/order_item')),
                array('qty' => new Zend_Db_Expr('SUM(order_item.qty_ordered)'))
            )
            ->where('product_id = ?', $productId, Zend_Db::INT_TYPE)
            ->where("(TO_DAYS('{$currentDate}') - TO_DAYS(order_item.created_at)) <= {$period}")
            ;
        return $this->getReadConnection()->fetchOne($select);
    }
}
