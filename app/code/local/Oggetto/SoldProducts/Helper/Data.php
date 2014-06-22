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
     * @var array
     */
    protected $_productTypes = array(
        'simple'
    );

    /**
     * @var array|null
     */
    protected $_qtyResults;

    /**
     * Do show sold products qty
     *
     * @param Mage_Catalog_Model_Product $product product instance
     * @return bool
     */
    public function doShowSoldProductsQyt(Mage_Catalog_Model_Product $product)
    {
        if ($this->isSupportedType($product)) {
            if (null === $enabled = $product->getData('sold_qty_enable')) {
                $enabled = Mage::getStoreConfigFlag('sold_products/default_settings/enabled');
            }
            return $enabled && ($this->_getSoldProductsQty($product) >= $this->getThreshold($product));
        }
        return false;
    }

    /**
     * Get sold products qty
     *
     * @param Mage_Catalog_Model_Product $product product
     * @return int
     */
    protected function _getSoldProductsQty(Mage_Catalog_Model_Product $product)
    {
        if (!isset($this->_qtyResults[$product->getId()])) {
            $this->_qtyResults[$product->getId()] =
                (int) Mage::getResourceSingleton('oggetto_soldproducts/soldProducts')
                        ->getSoldProductsQty($product->getId(), $this->getPeriod($product));
        }
        return $this->_qtyResults[$product->getId()];
    }

    /**
     * Get sold products qty to show on front
     *
     * @param Mage_Catalog_Model_Product $product product
     * @return bool|string
     */
    public function getSoldProductsQty(Mage_Catalog_Model_Product $product)
    {
        return $this->_getSoldProductsQty($product) + $this->getExtraQty($product);
    }

    /**
     * Get period
     *
     * @param Mage_Catalog_Model_Product $product product
     * @return int
     */
    public function getPeriod(Mage_Catalog_Model_Product $product)
    {
        return $this->_getNumericData(
            $product,
            'sold_qty_period',
            Mage::getStoreConfig('sold_products/default_settings/period')
        );
    }

    /**
     * Get threshold
     *
     * @param Mage_Catalog_Model_Product $product product
     * @return int
     */
    public function getThreshold(Mage_Catalog_Model_Product $product)
    {
        return $this->_getNumericData(
            $product,
            'sold_qty_threshold',
            Mage::getStoreConfig('sold_products/default_settings/qty_threshold')
        );
    }

    /**
     * Get extra qty
     *
     * @param Mage_Catalog_Model_Product $product product
     * @return int
     */
    public function getExtraQty(Mage_Catalog_Model_Product $product)
    {
        return $this->_getNumericData(
            $product,
            'sold_qty_extra',
            Mage::getStoreConfig('sold_products/default_settings/extra_qty')
        );
    }

    /**
     * Retrieve numeric attribute value
     *
     * @param Mage_Catalog_Model_Product $product      product
     * @param string                     $attribute    attribute code
     * @param int|string                 $defaultValue default value
     * @return int
     */
    protected function _getNumericData($product, $attribute, $defaultValue)
    {
        $value = $product->getData($attribute);
        if (!is_numeric($value)) {
            $value = $defaultValue;
        }
        return (int) $value;
    }

    /**
     * Check if product type is supported
     *
     * @param Mage_Catalog_Model_Product $product product
     * @return bool
     */
    public function isSupportedType(Mage_Catalog_Model_Product $product)
    {
        return in_array($product->getTypeId(), $this->_productTypes);
    }
}
