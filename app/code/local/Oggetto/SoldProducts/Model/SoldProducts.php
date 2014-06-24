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
 * Sold products model
 *
 * @category   Oggetto
 * @package    Oggetto_SoldProducts
 * @subpackage Model
 * @author     Dmitry Buryak <b.dmitry@oggettoweb.com>
 */
class Oggetto_SoldProducts_Model_SoldProducts
{
    /**
     * @var array
     */
    protected $_productTypes = array(
        'simple'
    );

    /**
     * @var Oggetto_SoldProducts_Model_Resource_SoldProducts
     */
    protected $_resource;

    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $_product;

    /**
     * @var array|null
     */
    protected $_qtyResults;

    /**
     * Constructor
     *
     * @param array|null $args constructor args
     */
    public function __construct(array $args = null)
    {
        if (isset($args['product'])) {
            $this->setProduct($args['product']);
        }
    }

    /**
     * Get sold products qty
     *
     * @return int
     */
    public function getSoldQty()
    {
        return $this->getSoldQtyReal() + $this->getExtraQty();
    }

    /**
     *  get sold products qty for category page
     *
     * @return int
     */
    public function getSoldQtyForCategoryPage()
    {
        return $this->getSoldQtyReal(false) + $this->getExtraQty();
    }

    /**
     * Get real value of sold qty
     *
     * @param bool $retrieveFromDb retrieve data from db
     * @return int
     */
    public function getSoldQtyReal($retrieveFromDb = true)
    {
        $productId = (int) $this->getProduct()->getId();
        if (!isset($this->_qtyResults[$productId])) {
            if ($retrieveFromDb) {
                $this->_qtyResults[$productId] = (int) $this->getResource()
                    ->getSoldProductsQty($productId, $this->getPeriod());
            } else {
                return 0;
            }
        }
        return $this->_qtyResults[$productId];
    }

    /**
     * Init qty results
     *
     * @param array $productIds product ids
     * @return $this
     */
    public function initQtyResults(array $productIds)
    {
        $result = $this->getResource()->getSoldQtyForCollection($productIds);
        foreach ($result as $row) {
            $this->_qtyResults[(int) $row['product_id']] = $row['qty'];
        }
        return $this;
    }

    /**
     * Check if sold qty period has changed
     *
     * @return bool
     */
    public function dataHasChangedForPeriod()
    {
        return $this->getProduct()->dataHasChangedFor('sold_qty_period');
    }

    /**
     * Get period
     *
     * @return int
     */
    public function getPeriod()
    {
        return (int) $this->_getNumericData(
            $this->getProduct(),
            'sold_qty_period',
            Mage::getStoreConfig('sold_products/default_settings/period')
        );
    }

    /**
     * Get threshold
     *
     * @return int
     */
    public function getThreshold()
    {
        return (int) $this->_getNumericData(
            $this->getProduct(),
            'sold_qty_threshold',
            Mage::getStoreConfig('sold_products/default_settings/qty_threshold')
        );
    }

    /**
     * Get extra qty
     *
     * @return int
     */
    public function getExtraQty()
    {
        return (int) $this->_getNumericData(
            $this->getProduct(),
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
        return $value;
    }

    /**
     * Get resource
     *
     * @return Oggetto_SoldProducts_Model_Resource_SoldProducts
     */
    public function getResource()
    {
        if (null === $this->_resource) {
            $this->_resource = Mage::getResourceModel('oggetto_soldproducts/soldProducts');
        }
        return $this->_resource;
    }

    /**
     * Set product
     *
     * @param Mage_Catalog_Model_Product $product product
     * @return Oggetto_SoldProducts_Model_SoldProducts
     */
    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        $this->_product = $product;
        return $this;
    }

    /**
     * Get product
     *
     * @return Mage_Catalog_Model_Product
     * @throws RuntimeException
     */
    public function getProduct()
    {
        if (null === $this->_product) {
            throw new RuntimeException(sprintf('Require set product before call %s', __METHOD__));
        }
        return $this->_product;
    }

    /**
     * Check if product type is supported
     *
     * @param string|null $type product type
     * @return bool
     */
    public function isSupportedProductType($type = null)
    {
        $type = $type ?: $this->getProduct()->getTypeId();
        return in_array($type, $this->_productTypes);
    }
}
