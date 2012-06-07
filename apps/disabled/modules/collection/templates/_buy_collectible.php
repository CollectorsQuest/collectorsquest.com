<?php
  /* @var $collectible_for_sale CollectibleForSale */
  $collectible_for_sale = $collectible->getCollectibleForSale();

  $offerPrice = $collectible_for_sale->getPrice();
  if ($collectible_for_sale)
  {
    $offer = $collectible_for_sale->getCollectibleOfferByBuyer($sf_user->getId(), 'counter');
    if ($offer)
    {
      $offerPrice = $offer->getPrice();
    }
    $isSold = $collectible_for_sale->getIsSold() || $collectible_for_sale->getActiveCollectibleOffersCount();
  }
  ?>
<a name="price" id="price"></a>
<br clear="all" class="dist-m20">

<?php if ($collectible_for_sale->getPrice() > 0): ?>

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
    <?php endif; ?>
  </div>

  <div class="span-3 center">
    <?= form_tag(url_for('marketplace_buy_now', $collectible_for_sale), array('method' => 'GET'));?>
    <p class="green-text bug-price"><?= money_format('%.2n', $offerPrice); ?></p>
    <?php if (!$sf_user->isAuthenticated() or $sf_user->getCollector()->getId() !== $collectible_for_sale->getCollector()->getId()): ?>
    <?php if ($sf_user->isAuthenticated()): ?>
      <?php cq_button_submit(__('Buy Now'), null, 'margin: 3px auto 3px auto; text-align: center;'); ?>
      <?php else: ?>
      <?= link_to('Sign in to buy', 'marketplace_buy_now', $collectible_for_sale, array('query_string'=>'goto='.urlencode($sf_request->getUri()), 'title' => 'Sign in to CQ to buy')); ?>
      <?php endif; ?>

    <?php endif; ?>
    </form>
  </div>

</div>
<?php endif; ?>
