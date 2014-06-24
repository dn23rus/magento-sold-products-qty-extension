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
 * Order listener
 *
 * @category   Oggetto
 * @package    Oggetto_SoldProducts
 * @subpackage Model
 * @author     Dmitry Buryak <b.dmitry@oggettoweb.com>
 */
class Oggetto_SoldProducts_Model_OrderListener
{
    /**
     * @var Oggetto_SoldProducts_Model_SoldProducts
     */
    protected $_soldQtyProductsModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_soldQtyProductsModel = Mage::getModel('oggetto_soldproducts/soldProducts');
    }

    /**
     * Save order items data
     *
     * @param Mage_Sales_Model_Order $order order instance
     * @return Oggetto_SoldProducts_Model_OrderListener
     */
    public function saveOrderItemsData(Mage_Sales_Model_Order $order)
    {
        $toInsert = array();
        foreach ($order->getAllItems() as $item) {
            /** @var Mage_Sales_Model_Order_Item $item */
            if ($this->_soldQtyProductsModel->isSupportedProductType($item->getProductType())) {
                $toInsert[] = array(
                    'product_id'    => $item->getProductId(),
                    'date_time'     => $item->getCreatedAt(),
                    'qty_ordered'   => $item->getQtyOrdered(),
                );
            }
        }
        if (!empty($toInsert)) {
            $this->_soldQtyProductsModel->getResource()->logSoldProductsQty($toInsert);
        }
        return $this;
    }
}
