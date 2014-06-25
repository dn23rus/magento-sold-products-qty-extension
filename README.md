## Install via composer

Update your `composer.json` like this

```JSON
    "require": {
        ...
        "magento-hackathon/magento-composer-installer":"*",
        "dn23rus/magento-sold-products-qty-extension" : "2.*"
        ...
    },
    "repositories": [
    ...
        {
            "type": "vcs",
            "url": "https://github.com/dn23rus/magento-sold-products-qty-extension"
        }
    ],
    ...
    "extra":{
        "magento-root-dir": ".",
    }
```

Optionally you can add

```JSON
    "extra":{
        "magento-deploystrategy": "copy",
        "auto-append-gitignore": true
    }
```

See more information about composer installer for magento at [github repository](https://github.com/magento-hackathon/magento-composer-installer/blob/master/README.md).

## Usage

### Product page:

Paste in your catalog/product/view.phtml following code:

```php
<?php if ($this->helper('oggetto_soldproducts')->doShowSoldProductsQty($_product)): ?>
    <div>
        <?php echo $this->__(
            '%d item(s) sold in the last %d day(s)',
            $this->helper('oggetto_soldproducts')->getSoldProductsQty($_product),
            $this->helper('oggetto_soldproducts')->getPeriod($_product)
        ) ?>
    </div>
<?php endif ?>
```

### Category page:

paste in catalog/product/list.phtml

```php
Mage::helper('oggetto_soldproducts')->initCollectionQtyResults($_productCollection);
```

and

```php
<?php if ($this->helper('oggetto_soldproducts')->doShowSoldProductsQty($_product, true)): ?>
    <p>
        <?php echo $this->__(
            '%d item(s) sold in the last %d day(s)',
            $this->helper('oggetto_soldproducts')->getSoldProductsQty($_product, true),
            $this->helper('oggetto_soldproducts')->getPeriod($_product)
        ) ?>
    </p>
<?php endif ?>
```
for list and grid mode
