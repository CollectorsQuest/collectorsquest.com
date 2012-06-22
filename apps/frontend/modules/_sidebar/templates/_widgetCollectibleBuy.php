
<?php if ($collectible_for_sale->getIsSold()): ?>

  <div id="price-container">
    <p class="price">
      Sold
      <small>
        for <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </small>
    </p>
    Quantity sold: 1
  </div>

<?php elseif ($collectible_for_sale->isForSale()): ?>

  <form action="<?= url_for('@shopping_cart', true); ?>" method="post">
    <div id="price-container">
      <p class="price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>

        <?php if ($collectible_for_sale->isShippingFree()): ?>
          <small style="white-space: nowrap;">with FREE shipping & handling</small>
        <?php endif; ?>
      </p>
      <button type="submit" class="btn btn-primary pull-left" value="Add Item to Cart">
        <i class="add-to-card-button"></i>
        <span>Add Item to Cart</span>
      </button>
    </div>

    <?= $form->renderHiddenFields(); ?>
  </form>

<?php endif; ?>
