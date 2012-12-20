<?php
/**
 * @var $collectible Collectible
 * @var $shopping_order ShoppingOrder
 * @var $shopping_payment ShoppingPayment
 * @var $pm_form ComposeAbridgedPrivateMessageForm
 */
?>
<?php
  $link = link_to(
    'Back to Items for Sale &raquo;', '@mycq_marketplace',
    array('class' => 'text-v-middle link-align')
  );

  cq_section_title(
    $collectible->getName(), $link,
    array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
  );
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
        $collectible->getPrimaryImage(), '300x0', array('width' => 294, 'height' => null)
      );
    ?>
    </div>
  </div><!-- ./span4 -->
</div>

<?php
  cq_section_title(
    'Shipping Information', null,
    array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
  );
?>
<div class="row-fluid">
  <div class="span8 spacer-left-reset">
    <table class="table table-collectible-purchased">
      <tr>
        <td>Email Address:</td>
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
          <?php else: ?>
            <div class="row-fluid">
            <form action="<?= url_for('mycq_shopping_order_tracking', $shopping_order); ?>" method="post">
              <div class="span4 spacer-left-reset">
                <select name="carrier" style="width: 100px;">
                  <option value="">Courier</option>
                  <option value="">-------</option>
                  <option value="FedEx">FedEx</option>
                  <option value="UPS">UPS</option>
                  <option value="USPS">USPS</option>
                </select>
              </div>
              <div class="span8">
                <input type="text" name="tracking_number" placeholder="enter the tracking number here"/>
              </div>
              <button type="submit" class="btn btn-primary">Mark as Shipped</button>
            </form>
            </div>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <div class="span4">
    <div class="send-pm">
      <?= form_tag('@messages_compose'); ?>
      <?= $pm_form->renderHiddenFields(); ?>
      <?= $pm_form['body']->render(); ?>
      <button type="submit" class="btn-lightblue-normal textright" style="float: right; margin-top: 10px;">
        <i class="mail-icon-mini"></i> &nbsp;Send message
      </button>
      <?= '</form>'; ?>
    </div>
  </div>
</div>

<?php
  cq_section_title(
    'Order Information', null,
    array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
  );
?>
<div class="row-fluid">
  <div class="span8 spacer-left-reset">
    <table class="table table-collectible-purchased">
      <tr>
        <td>Payment Status:</td>
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
        <td>
          <span class="f-20">
            Total Amount:
          </span>
        </td>
        <td>
          <span class="f-20">
          <?= money_format('%.2n', (float) $shopping_order->getTotalAmount()) ?>
          </span>
        </td>
      </tr>
    </table>
  </div>
</div>

<?php
  cq_section_title(
    'PayPal Transaction', null,
    array('left' => 8, 'right' => 4, 'class'=>'row-fluid sidebar-title spacer-top-20')
  );
?>
<table class="table table-collectible-purchased">
  <tr>
    <td>Payment Status:</td>
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

