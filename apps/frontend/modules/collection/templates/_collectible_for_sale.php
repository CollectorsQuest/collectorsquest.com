<?php
  $shipping_will_ship_text = '';
  $buy_form = new CollectibleForSaleBuyForm($collectible_for_sale);
  $shipping_no_shipping_countries = array();
?>

<!-- sale items -->
<span class="item-condition">
  <strong>Condition:</strong>
  <?= $collectible_for_sale->getCondition(); ?>
</span>

<table class="shipping-rates">
  <thead>
  <tr class="shipping-dest">
    <th colspan="2">
      <strong>Shipping from:</strong> <span class="darkblue">
          <?= $collector->getProfile()->getCountryName() ?: '-'; ?>
        </span>
    </th>
  </tr>
  <tr class="dotted-line-brown">
    <th>SHIP TO</th>
    <th>COST</th>
  </tr>
  </thead>
  <tbody>

  <?php if (count($collectible->getShippingReferencesByCountryCode())):
    foreach ($collectible->getShippingReferencesByCountryCode() as $country_code => $shipping_reference):
      if (ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING != $shipping_reference->getShippingType()): ?>

        <?php ob_start(); ?>
        <tr>
          <td><?= $shipping_reference->getCountryName(); ?></td>
          <td>
            <?php if ($shipping_reference->isSimpleFreeShipping()): ?>
            Free shipping
            <?php else: ?>
            $<?= $shipping_reference->getSimpleShippingAmount(); ?>
            <?php endif; ?>
          </td>
        </tr>
          <?php $shipping_will_ship_text .= ob_get_clean(); ?>

          <?php else: // shipping_type = no shipping
          $shipping_no_shipping_countries[] = $shipping_reference->getCountryName();

        endif;
      endforeach; // foreach shipping reference ?>

      <?= $shipping_will_ship_text; // first output which countries we ship to ?>

      <?php $international_shipping = $collectible->getShippingReferenceForCountryCode('ZZ'); ?>
      <?php if ($international_shipping && ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $international_shipping->getShippingType()): ?>
        <tr>
          <td>International Shipping</td>
          <td>This item cannot be shipped internationally</td>
        </tr>
      <?php elseif ($shipping_no_shipping_countries): ?>
        <tr>
          <td>This item cannot be shipped to the following countries</td>
          <td><?= implode($shipping_no_shipping_countries, ', '); ?></td>
        </tr>
      <?php endif; ?>
    <?php else: ?>
      <tr>
        <td>United States</td>
        <td>Free shipping</td>
      </tr>
      <tr>
        <td>Everywhere Else</td>
        <td>Free shipping</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<div id="information-box">
  <?php
    if ($collector->getId() == 6668)
    {
      $send_text = 'Send a message to the folks at HISTORY here »';
    }
    else
    {
      $send_text = sprintf('Send a message to %s »', $collector->getDisplayName());
    }
  ?>
  <p>Have a question about shipping? <?= cq_link_to(
    $send_text,
    'messages_compose',
    array(
      'to' => $collector->getUsername(),
      'subject' => 'Regarding your item: '. $collectible->getName(),
      'goto' => url_for_collectible($collectible)
    )
  ); ?></p>

  <?php if (false && 0 != $collectible_for_sale->getTaxPercentage()): ?>
    <p>
      <strong>Tax:</strong> <?= $collectible_for_sale->getTaxPercentage(); ?>%
        for <?= $collectible_for_sale->getTaxState()
      ? iceModelGeoRegionPeer::retrieveByPK($collectible_for_sale->getTaxState())->getNameLatin() . ' /' : ''; ?>
        <?= $collectible_for_sale->getIceModelGeoCountry()->getName(); ?>
    </p>
  <?php endif; ?>

  <?php if ($refunds_policy = $collector->getSellerSettingsRefunds()): ?>
    <p><strong>Refunds Policy:</strong> <?= $refunds_policy ?></p>
  <?php endif; ?>

  <?php if ($shipping_policy = $collector->getSellerSettingsShipping()): ?>
    <p class="truncate"><strong>Shipping Policy:</strong> <?= nl2br($shipping_policy); ?></p>
  <?php endif; ?>
</div>

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
<?php elseif ($collectible_for_sale->isForSale() && cqGateKeeper::open('shopping_cart')): ?>
  <form action="<?= url_for('@shopping_cart', true); ?>" method="post">
    <div id="price-container">
      <p class="price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>

        <?php if ($collectible_for_sale->isShippingFree()): ?>
        <small class="text-nowrap">with FREE shipping & handling</small>
        <?php endif; ?>
      </p>
      <button type="submit" class="btn btn-primary pull-left" value="Add Item to Cart">
        <i class="add-to-card-button"></i>
        <span>Add Item to Cart</span>
      </button>
    </div>

    <?= $buy_form->renderHiddenFields(); ?>
  </form>
<?php endif; // if for sale ?>

<?php if ($collector->getId() == 6668): ?>
<script>
  $(document).ready(function ()
  {
    $('.truncate').expander({
      slicePoint: 250,
      widow: 2,
      expandEffect: 'show',
      expandText: ' Read more >>',
      expandPrefix: '',
      userCollapseText: '[^]',
      onSlice: function() { $(this).show(); }
    })
    .show();
  });
</script>
<?php endif; ?>
