<?php
/**
 * @var  $seller  Collector
 * @var  $shopping_order    ShoppingOrder
 * @var  $shopping_payment  ShoppingPayment
 */
?>

<script>
  if (window != top) {
    top.location.replace(document.location);
  }
</script>

<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Shipping'), __('Payment'), __('Order Review')) , 'active' => 3)
  );
?>

<h2 class="spacer-top-35">
  <?= $shopping_order->getShippingFullName() ?>, thank you for your purchase!
</h2>

<p class="spacer-top-20">
  The seller, <?= link_to_collector($seller) ?>, has been notified and
  will be in contact with you soon to finalize any outstanding details if needed.
  You should receive a confirmation email when your item ships.
</p>

<?php if (!$sf_user->isAuthenticated()): ?>
  <p>
    In order to streamline your future shopping experience and track your orders,
    please <strong>sign up for an account</strong> using the form to the right.
  </p>
<?php endif; ?>

<p>Here are your order details:</p><br/>

<div class="row-fluid">

  <?php
    cq_sidebar_title(
      'Order Information', null,
      array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
    );
  ?>
  <div class="span7 spacer-left-reset">
    <table class="table table-collectible-purchased">
      <tr>
        <td>Status:</td>
        <td>
          <?= strtoupper($shopping_order->getShoppingPaymentRelatedByShoppingPaymentId()->getStatus()); ?>
        </td>
      </tr>
      <tr>
        <td>Order #:</td>
        <td><?= $shopping_order->getUuid() ?></td>
      </tr>
      <tr>
        <td>Date:</td>
        <td><?= $shopping_order->getCreatedAt() ?></td>
      </tr>
      <tr>
        <td>Shipping Address:</td>
        <td>
          <?= $shopping_order->getShippingAddressLine1(); ?>
          <p>
            <?= $shopping_order->getShippingCity(); ?>,
            <?= $shopping_order->getShippingStateRegionName(); ?>
            <?= $shopping_order->getShippingZipPostcode(); ?>
          </p>
          <p style="font-weight: bold;"><?= $shopping_order->getShippingCountryName(); ?></p>
        </td>
      </tr>
      <?php if ($v = $shopping_order->getNoteToSeller()): ?>
      <tr>
        <td>Extra Information:</td>
        <td><?= $v; ?></td>
      </tr>
      <?php endif; ?>
    </table>
  </div>

  <div class="span5">
    <table class="table table-collectible-purchased">
      <tr>
        <td>Item Price:</td>
        <td>1 × <?= money_format('%.2n', (float) $shopping_order->getCollectiblesAmount()) ?></td>
      </tr>
      <?php if (0 != $shopping_order->getTaxAmount('integer')): ?>
      <tr>
        <td>Tax (<?= $shopping_order->getCollectibleForSale()->getTaxPercentage(); ?>%):</td>
        <td><?= money_format('%.2n', (float) $shopping_order->getTaxAmount()) ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td>Shipping Fee:</td>
        <td><?= money_format('%.2n', (float) $shopping_order->getShippingFeeAmount()) ?></td>
      </tr>
      <tr>
        <td class="f-18 text-bold">Total Amount:</td>
        <td class="f-18 text-bold"><?= money_format('%.2n', (float) $shopping_order->getTotalAmount()) ?></td>
      </tr>
    </table>
  </div>
</div>

<div class="row-fluid">
  <div class="span8 spacer-left-reset">
    <table class="table table-collectible-purchased">
      <tr>
        <td>Item Name:</td>
        <td><?= $collectible->getName(); ?></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><?= $collectible->getDescription(); ?></td>
      </tr>
      <tr>
        <td>Price:</td>
        <td><?= money_format('%.2n', (float) $shopping_order->getCollectiblesAmount()); ?></td>
      </tr>
      <tr>
        <td>Condition:</td>
        <td><?= $collectible->getCollectibleForSale()->getCondition(); ?></td>
      </tr>
    </table>
  </div><!-- ./span8 -->

  <div class="span4">
    <div class="thumbnail">
    <?php
      echo image_tag_multimedia(
        $collectible->getPrimaryImage(), '190x190'
      );
    ?>
    </div>
  </div><!-- ./span4 -->
</div>

<script>
if (Modernizr.isproduction)
{
  _gaq.push(['_addTrans',
    '<?= $shopping_order->getUuid() ?>',
    '<?= addcslashes($shopping_order->getSeller()->getDisplayName(), "'") ?>',
    '<?= number_format((float) $shopping_order->getTotalAmount(), 2) ?>',
    '0',
    '<?= number_format((float) $shopping_order->getShippingFeeAmount(), 2) ?>',
    '<?= addcslashes($shopping_order->getShippingCity(), "'") ?>',
    '<?= addcslashes($shopping_order->getShippingStateRegionName(), "'") ?>',
    '<?= $shopping_order->getShippingCountryName() ?>'
  ]);

  _gaq.push(['_addItem',
    '<?= $shopping_order->getUuid() ?>',
    '<?= $collectible->getId(); ?>',
    '<?= addcslashes($collectible->getName(), "'") ?>',
    '<?= addcslashes($collectible->getContentCategory(), "'") ?>',
    '<?= number_format((float) $shopping_order->getCollectiblesAmount(), 2) ?>',
    '1'
  ]);

  // Submits transaction to the Analytics servers
  _gaq.push(['_trackTrans']);
}
</script>
