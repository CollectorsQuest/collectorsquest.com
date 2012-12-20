<?php
/**
 * @var $collectible Collectible
 * @var $shopping_order ShoppingOrder
 */
?>

<?php
$link = link_to(
  'Back to Purchases &raquo;', '@mycq_marketplace_purchased',
  array('class' => 'text-v-middle link-align')
);
cq_section_title(
  'Order Information', $link,
  array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
);
?>

<div class="spacer-20">
  <div class="row-fluid gray-bg">
    <div class="span8">
      <table class="table table-striped table-collectible-purchased">
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
        <tr>
          <td>Shipping Address:</td>
          <td>
            <?= $shopping_order->getShippingAddressLine1(); ?>
            <p>
              <?= $shopping_order->getShippingCity(); ?>,
              <?= $shopping_order->getShippingStateRegionName(); ?>
              <?= $shopping_order->getShippingZipPostcode(); ?>
            </p>
            <p class="text-bold"><?= $shopping_order->getShippingCountryName(); ?></p>
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
            N/A
            <?php endif; ?>
          </td>
        </tr>
      </table>
    </div>
    <div class="span4">
      <table class="table table-price-purchased">
        <tr>
          <td>
          <span class="f-14">
            Item Price:
          </span>
          </td>
          <td>
          <span class="f-14">
            1 × <?= money_format('%.2n', (float) $shopping_order->getCollectiblesAmount()) ?>
          </span>
          </td>
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
        <?php if (0 != $shopping_order->getTaxAmount('integer')): ?>
          <tr>
            <td>
            <span class="f-14">
              Tax (<?= $shopping_order->getCollectibleForSale()->getTaxPercentage(); ?>%):
            </span>
            </td>
            <td>
            <span class="f-14">
              <?= money_format('%.2n', (float) $shopping_order->getTaxAmount()) ?>
            </span>
            </td>
          </tr>
        <?php endif; ?>
        <tr>
          <td>
          <span class="f-14">
            Shipping Fee:
          </span>
          </td>
          <td>
          <span class="f-14">
            <?= money_format('%.2n', (float) $shopping_order->getShippingFeeAmount()) ?>
          </span>
          </td>
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
</div>



<?php
  cq_section_title(
    $collectible->getName(), null,
    array('left' => 8, 'right' => 4, 'class'=>'row-fluid sidebar-title')
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
  </div>
  <div class="span4">
    <div class="thumbnail">
      <?php
      echo image_tag_multimedia(
        $collectible->getPrimaryImage(), '300x0', array('width' => 294, 'height' => null)
      );
      ?>
    </div>
  </div>
</div>


<?php
cq_section_title(
  'Seller Information', null,
  array('left' => 8, 'right' => 4, 'class'=>'row-fluid sidebar-title')
);
?>

<div class="row-fluid">
  <div class="span8">
    <table class="table table-collectible-purchased">
      <tr>
        <td>Name:</td>
        <td>
          <?= $shopping_order->getSeller(); ?>
          <a href="<?= url_for_collector($shopping_order->getSeller()); ?>">
            (View Profile)
          </a>
        </td>
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
      <?php if ($v = $shopping_order->getShippingPhone()): ?>
      <tr>
        <td>Phone Number:</td>
        <td><?= $v; ?></td>
      </tr>
      <?php endif; ?>
    </table>
  </div>
  <div class="span4">
    <div class="send-pm cf">
      <?= form_tag('@messages_compose'); ?>
      <?= $pm_form->renderHiddenFields(); ?>
      <?= $pm_form['body']->render(); ?>
      <button type="submit" class="btn-lightblue-normal textright" style="float: right; margin-top: 10px;">
        <i class="mail-icon-mini"></i> &nbsp;Send message
      </button>
      <?= '</form>'; ?>
    </div> <!-- ./send-pm -->
  </div>
</div>


