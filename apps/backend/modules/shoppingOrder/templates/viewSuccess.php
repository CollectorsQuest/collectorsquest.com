<?php
/**
 * @var $collectible Collectible
 * @var $shopping_order ShoppingOrder
 */
?>
<div class="row-fluid">
  <div class="span8">
    <table class="table table-collectible-purchased">
      <tr>
        <td>Name:</td>
        <td><?= $collectible->getName(); ?></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><?= $collectible->getDescription(); ?></td>
      </tr>
      <?php if ($v = $collectible->getTagString()): ?>
      <tr>
        <td>Tags:</td>
        <td><?= $v; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td>Price:</td>
        <td><?= money_format('%.2n', (float) $collectible->getCollectibleForSale()->getPrice()); ?></td>
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
          $collectible->getPrimaryImage(), '300x0', array('width' => 300, 'height' => null)
        );
      ?>
    </div>
  </div><!-- ./span4 -->
</div>

<h3>Seller Information</h3>
<div class="row-fluid">
  <div class="span8">
    <table class="table table-collectible-purchased">
      <tr>
        <td style="width: 40%;">Name:</td>
        <td><?= $shopping_order->getSeller(); ?></td>
      </tr>
      <tr>
        <td>Email Address:</td>
        <td>
          <?php
          echo mail_to(
            $shopping_order->getSeller()->getEmail(),
            $shopping_order->getSeller()->getEmail()
          );
          ?>
        </td>
      </tr>
    </table>
  </div>
  <div class="span4">
    &nbsp;
  </div>
</div>



<h3>Buyer Information</h3>
<div class="row-fluid">
  <div class="span8 spacer-left-reset">
    <table class="table table-collectible-purchased">
      <tr>
        <td style="width: 40%;">Email Address:</td>
        <td>
          <?php
          echo mail_to(
            $shopping_order->getBuyerEmail(),
            $shopping_order->getBuyerEmail()
          );
          ?>
        </td>
      </tr>
      <tr>
        <td>Name:</td>
        <td><?= $shopping_order->getShippingFullName(); ?></td>
      </tr>
      <?php if ($v = $shopping_order->getShippingPhone()): ?>
      <tr>
        <td>Phone Number:</td>
        <td><?= $v; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td>Shipping Address:</td>
        <td>
          <?= $shopping_order->getShippingAddressLine1(); ?>
          <?php
            $al2 = $shopping_order->getShippingAddressLine2();
            echo $al2 ? '<br/>'. $al2 : '';
          ?>
          <p>
            <?= $shopping_order->getShippingCity(); ?>,
            <?= $shopping_order->getShippingStateRegionName(); ?>
            <?= $shopping_order->getShippingZipPostcode(); ?>
          </p>
          <p><strong><?= $shopping_order->getShippingCountryName(); ?></strong></p>
        </td>
      </tr>
      <?php if ($v = $shopping_order->getNoteToSeller()): ?>
      <tr>
        <td>Extra Information:</td>
        <td><?= $v; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td>Tracking Number:</td>
        <td>
          <?php if ($shopping_order->getShippingTrackingNumber()): ?>
          <a href="http://www.faranow.com/track/<?= strtoupper($shopping_order->getShippingCarrier()) ?>/<?= $shopping_order->getShippingTrackingNumber() ?>"
             target="_blank">
            <?= $shopping_order->getShippingTrackingNumber() ?>
          </a>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <div class="span4">

  </div>
</div>

<h3>Order Information</h3>
<div class="row-fluid">
  <div class="span8 spacer-left-reset">
    <table class="table table-collectible-purchased">
      <tr>
        <td style="width: 40%;">Payment Status:</td>
        <td>
          <?= strtoupper($shopping_order->getShoppingPaymentRelatedByShoppingPaymentId()->getStatus()); ?>
        </td>
      </tr>
      <tr>
        <td>Order #:</td>
        <td><?= $shopping_order->getUuid() ?></td>
      </tr>
      <tr>
        <td>Date & Time:</td>
        <td><?= $shopping_order->getCreatedAt() ?></td>
      </tr>
    </table>
  </div>

  <div class="span4">
    <table class="table table-collectible-purchased">
      <tr>
        <td>Item Price:</td>
        <td>1 × <?= money_format('%.2n', (float) $shopping_order->getCollectiblesAmount()) ?></td>
      </tr>
      <?php if ($shopping_order->getSellerPromotionId()): ?>
      <tr>
        <td>
          <?= $shopping_order->getSellerPromotion()->getPromotionName(); ?>
        </td>
        <td>
          <?php if (0 != $shopping_order->getPromotionAmount('integer')): ?>
          - <?= money_format('%.2n', (float) $shopping_order->getPromotionAmount()) ?>
          <?php else: ?>
          &nbsp;
          <?php endif; ?>
        </td>
      </tr>
      <?php endif; ?>
      <?php if (($v = $shopping_order->getTaxAmount('float')) && 0 != (int) $v): ?>
        <tr>
          <td>Tax Fee:</td>
          <td><?= money_format('%.2n', (float) $v); ?></td>
        </tr>
      <?php endif; ?>
      <tr>
        <td>Shipping Fee:</td>
        <td><?= money_format('%.2n', (float) $shopping_order->getShippingFeeAmount()) ?></td>
      </tr>
      <tr>
        <td>
          <span class="f-20">
            <strong>Total Amount:</strong>
          </span>
        </td>
        <td>
          <span class="f-20">
          <strong><?= money_format('%.2n', (float) $shopping_order->getTotalAmount()) ?></strong>
          </span>
        </td>
      </tr>
    </table>
  </div>
</div>

<h3>PayPal Transaction</h3>
<table class="table table-collectible-purchased">
  <tr>
    <td style="width: 26%;">Payment Status:</td>
    <td><?= strtoupper($shopping_payment->getStatus()); ?></td>
  </tr>
  <?php if ($v = $shopping_payment->getTransactionId()): ?>
  <tr>
    <td>Transaction ID:</td>
    <td><?= $v ?></td>
  </tr>
  <?php endif; ?>
  <?php if ($v = $shopping_payment->getSenderEmail()): ?>
  <tr>
    <td>Sender Email:</td>
    <td><?= $v; ?></td>
  </tr>
  <?php endif; ?>
</table>
