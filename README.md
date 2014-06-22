## Usage

Paste in your catalog/product/view.phtml following code:

```php
<?php if ($this->helper('oggetto_soldproducts')->doShowSoldProductsQyt($_product)): ?>
    <div>
        <?php echo $this->__(
            '%d item(s) sold in the last %d day(s)',
            $this->helper('oggetto_soldproducts')->getSoldProductsQty($_product),
            $this->helper('oggetto_soldproducts')->getPeriod($_product)
        ) ?>
    </div>
<?php endif ?>
```
