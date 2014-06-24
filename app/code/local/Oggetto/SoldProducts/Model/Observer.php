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
 * Observer
 *
 * @category   Oggetto
 * @package    Oggetto_SoldProducts
 * @subpackage Model
 * @author     Dmitry Buryak <b.dmitry@oggettoweb.com>
 */
class Oggetto_SoldProducts_Model_Observer
{
    /**
     * Save order items data
     *
     * @param Varien_Event_Observer $observer observer instance
     * @return void
     */
    public function saveItemsData(Varien_Event_Observer $observer)
    {
        try {
            Mage::getModel('oggetto_soldproducts/orderListener')
                ->saveOrderItemsData($observer->getEvent()->getData('order'));
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
}
