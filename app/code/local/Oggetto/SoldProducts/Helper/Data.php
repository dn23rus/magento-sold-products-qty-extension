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
 * Default helper
 *
 * @category   Oggetto
 * @package    Oggetto_SoldProducts
 * @subpackage Helper
 * @author     Dmitry Buryak <b.dmitry@oggettoweb.com>
 */
class Oggetto_SoldProducts_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var Oggetto_SoldProducts_Model_SoldProducts
     */
    protected $_soldProductsModel;

    /**
     * Load qty data for collection items
     *
     * @param Mage_Eav_Model_Entity_Collection_Abstract $productCollection product collection
     * @return Oggetto_SoldProducts_Helper_Data
     */
    public function initCollectionQtyResults(Mage_Eav_Model_Entity_Collection_Abstract $productCollection)
    {
        if ($this->doShowOnCategoryPage() && $productCollection->count()) {
            $this->_getSoldProductsModel($productCollection->getFirstItem())
                ->initQtyResults($productCollection->getAllIds());
        }
    }

    /**
     * Show on category page
     *
     * @return bool
     */
    public function doShowOnCategoryPage()
    {
        return Mage::getStoreConfigFlag('sold_products/default_settings/show_on_category_page');
    }

    /**
     * Check if show sold products qty
     *
     * @param Mage_Catalog_Model_Product $product        product instance
     * @param bool                       $isCategoryPage use in category page
     * @return bool
     */
    public function doShowSoldProductsQty(Mage_Catalog_Model_Product $product, $isCategoryPage = false)
    {
        if ($isCategoryPage && !$this->doShowOnCategoryPage()) {
            return false;
        }
        $soldProductsModel = $this->_getSoldProductsModel($product);
        if ($soldProductsModel->isSupportedProductType()) {
            if (null === ($enabled = $product->getData('sold_qty_enable'))) {
                $enabled = Mage::getStoreConfigFlag('sold_products/default_settings/enabled');
            }
            return $enabled &&
                $soldProductsModel->getSoldQtyReal(!$isCategoryPage) > $soldProductsModel->getThreshold();
        }
        return false;
    }

    /**
     * Get sold products model
     *
     * @param Mage_Catalog_Model_Product $product product instance
     * @return Oggetto_SoldProducts_Model_SoldProducts
     */
    protected function _getSoldProductsModel(Mage_Catalog_Model_Product $product)
    {
        if (null === $this->_soldProductsModel) {
            $this->_soldProductsModel = Mage::getModel('oggetto_soldproducts/soldProducts');
        }
        $this->_soldProductsModel->setProduct($product);
        return $this->_soldProductsModel;
    }

    /**
     * Get sold products qty to show on front
     *
     * @param Mage_Catalog_Model_Product $product        product
     * @param bool                       $isCategoryPage use in category page
     * @return bool|string
     */
    public function getSoldProductsQty(Mage_Catalog_Model_Product $product, $isCategoryPage = false)
    {
        return $isCategoryPage ?
            $this->_getSoldProductsModel($product)->getSoldQtyForCategoryPage() :
            $this->_getSoldProductsModel($product)->getSoldQty();
    }

    /**
     * Get period
     *
     * @param Mage_Catalog_Model_Product $product product
     * @return int
     */
    public function getPeriod(Mage_Catalog_Model_Product $product)
    {
        return $this->_getSoldProductsModel($product)->getPeriod();
    }
}
