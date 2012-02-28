<?php

/**
 * @var  $collector  Collector
 * @var  $collectible  Collectible
 * @var  $sf_user  cqUser
 */

/* @var $collectible_for_sale CollectibleForSale */
$collectible_for_sale = $collectible->getForSaleInformation();

$offerPrice = $collectible_for_sale->getPrice();
if ($collectible_for_sale)
{
  $offer = $collectible_for_sale->getCollectibleOfferByBuyer($sf_user->getId(), 'counter');
  if ($offer)
  {
    $offerPrice = $offer->getPrice();
  }
  $isSold = $collectible_for_sale->getIsSold() || $collectible_for_sale->getActiveCollectibleOffersCount();

  $form = new CollectibleForSaleBuyForm($collectible_for_sale);
}
?>

<?php if ($collectible_for_sale->getPrice() > 0): ?>

  <a name="price" id="price"></a>
  <br class="clear dist-m20">

  <?php echo cq_section_title('This item is for sale by ' . link_to_collector($collector, 'text')); ?>

  <div class="buy-now-container cf">
    <div class="span-5 append-1">
      Item condition
      <p class="green-text"><?= strtoupper($collectible_for_sale->getCondition()) . ' CONDITION'; ?></p>
    </div>
    <div class="span-5 append-2">
      <img src="/images/legacy/box-icon-mini.png" width="25" height="19" border="0" alt="" class="img-align-vmiddle" />Shopping cost
      <?php if ($collectible_for_sale->getIsShippingFree()): ?>
        <p class="blue-text">Free Shipping</p>
      <?php else: ?>
        <p class="blue-text">Not Specified</p>
      <?php endif; ?>
    </div>

    <div class="span-3 center">
      <form action="<?= url_for('@shopping_cart'); ?>" method="post">
        <?= $form->renderHiddenFields(); ?>
        <p class="green-text bug-price"><?= money_format('%.2n', (float) $offerPrice); ?></p>
        <?php
          if (!$sf_user->isAuthenticated() || $sf_user->getCollector()->getId() !== $collectible_for_sale->getCollector()->getId())
          {
            echo cq_button_submit(__('Add to Cart'), null, 'margin: 3px auto 3px auto; text-align: center;');
          }
        ?>
      </form>
    </div>
  </div>

<?php endif; ?>
