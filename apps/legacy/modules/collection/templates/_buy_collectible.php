<?php
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
}
?>
<table width="100%">
  <tr>
    <td style="width: 180px;">
      <?php
        echo form_tag(url_for('marketplace_buy_now', $collectible_for_sale), array('method' => 'GET'));
      ?>
      <div id="for_sale" style="padding: 10px; padding-top: 20px;">
        <span style="color: #EEA441; font-size: 14px; font-weight: bold;">
          <div class="section-title"> <?php echo section_title('This item is for sale by ' . link_to_collector($collector, 'text')); ?> </div>
        </span>
        <div style="border: 1px solid #D1D1D1; width: 320px; margin-top: 5px;">
          <?php if ($collectible_for_sale->getPrice() > 0): ?>
            <div style="background: #BBDC71; color: #fff; font-size: 20px; display: inline; text-align: center; padding: 6px;">
              <?php echo money_format('%.2n', $offerPrice); ?>
            </div>
            <?php if (!$sf_user->isAuthenticated() or $sf_user->getCollector()->getId() !== $collectible_for_sale->getCollector()->getId()): ?>
              <div style="display: inline; margin: 0 15%; padding: 5px 0 5px 0;">
                <?php if ($sf_user->isAuthenticated()): ?>
                  <?php cq_button_submit(__('Buy Now'), null, 'margin-top: 3px; float: right;'); ?>
                <?php else: ?>
                  <?php echo link_to('Sign in to buy', 'marketplace_buy_now', $collectible_for_sale, array('query_string'=>'goto='.urlencode($sf_request->getUri()), 'title' => 'Sign in to CQ to buy')); ?>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>
          <br clear="all">
          <div style="background: #F5F7DF; padding: 7px; color: #65A0B0;">
            <div style="float: right;">
              <?php if ($collectible_for_sale->getIsShippingFree()): ?>
                <div style="float: right; padding-top: 3px; margin-left: 5px; margin-right: 50px;">Free Shipping</div>
                <img src="/images/no-sh-info.png" alt=""/>
              <?php endif; ?>
            </div>
            <?php echo strtoupper($collectible_for_sale->getCondition()) . ' CONDITION'; ?> </div>
        </div>
      </div>
      </form>
    </td>
    <?php /*
      <td>
      <?php if ($collectible->isForSale()): ?>
      <div class="rounded buynow" style="margin-top: 35px;">
      <?php if (!$isSold): ?>
      <a href="<?php echo url_for('marketplace_buy_now', $collectible_for_sale) ?>"><?php echo $collectible_for_sale->getPrice() ? money_format('Buy for %.2n', $offerPrice) : __('Buy item') ?></a>
      <?php else: ?>
      <?php echo __('ITEM SOLD') ?>
      <?php endif; ?>
      </div>
      <?php endif; ?>
      </td>
     */ ?>
  </tr>
</table>
