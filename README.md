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

add

```php
$showOnCategoryPage = $this->helper('oggetto_soldproducts')->doShowOnCategoryPage();
```

and

```php
<?php if ($showOnCategoryPage && $this->helper('oggetto_soldproducts')->doShowSoldProductsQty($_product, true)): ?>
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
