<?php
/*
 * @var $collectible_for_sale CollectibleForSale
 * @var $height stdClass
 */

$_height = 0;
?>

<?php if ($collectible_for_sale->hasActiveCredit() && IceGateKeeper::open('shopping_cart') ): ?>

  <?php if ($collectible_for_sale->getIsSold()): ?>

    <div id="price-container">
      <p class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <link itemprop="availability" href="http://schema.org/Discontinued" />Sold
        <small>
          for <span itemprop="price"><?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?></span>
        </small>
      </p>
      Quantity sold: 1
    </div>
    <?php $_height -= 59; ?>
  <?php elseif ($collectible_for_sale->isForSale()): ?>
    <form action="<?= url_for('@shopping_cart', true); ?>" method="post">
      <div id="price-container" class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <p>
          <span itemprop="price">
            <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
          </span>

          <?php if ($collectible_for_sale->isShippingFree()): ?>
            <small class="text-nowrap">with FREE shipping & handling</small>
          <?php endif; ?>
        </p>
        <button type="submit" class="btn btn-primary pull-left" value="Add Item to Cart">
          <i class="add-to-card-button"></i>
          <span><link itemprop="availability" href="http://schema.org/InStock" />Add Item to Cart</span>
        </button>
      </div>

      <?= $form->renderHiddenFields(); ?>
    </form>
    <?php $_height -= 73; ?>
  <?php endif; // is for sale ?>

<?php elseif ($collectible_for_sale->isForSale()): // and has no active credit ?>
  <div id="price-container">
    <p class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
      <span itemprop="price"><?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?></span>
    </p>
    <button type="button" class="btn btn-primary blue-button pull-left" value="Add Item to Cart"
            onclick="$('#form-private-message').find('textarea').focus().click(); return false;">
      <span>Send a Message to the Seller</span>
    </button>
  </div>
  <?php $_height -= 73; ?>
<?php endif; ?>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
