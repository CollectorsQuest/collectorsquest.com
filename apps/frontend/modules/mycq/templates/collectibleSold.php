<?php
/**
 * @var $collectible Collectible
 * @var $shopping_order ShoppingOrder
 * @var $shopping_payment ShoppingPayment
 * @var $pm_form ComposeAbridgedPrivateMessageForm
 */
?>

<div class="row-fluid">
  <div class="span8">
    <?php
      $link = link_to(
        'Back to Items for Sale &raquo;', '@mycq_marketplace',
        array('class' => 'text-v-middle link-align')
      );

      cq_sidebar_title(
        $collectible->getName(), $link,
        array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
      );
    ?>

    <table class="table">
      <tr>
        <td style="width: 25%;">Name:</td>
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
    <div id="main-image-set">
      <div class="main-image-set-container">
        <ul class="thumbnails">
          <li class="span12 main-thumb">
            <div class="thumbnail">
            <?php
              echo image_tag_multimedia(
                $collectible->getPrimaryImage(), '300x0', array('width' => 294)
              );
            ?>
            </div>
          </li>
          <?php foreach ($multimedia as $m): ?>
          <li class="span4 square-thumb ui-state-full">
            <div class="thumbnail drop-zone" data-is-primary="0">
            <?php
              echo image_tag_multimedia(
                $m, '150x150', array('width' => 92, 'height' => 92)
              );
            ?>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

  </div><!-- ./span4 -->

  <?php
    cq_sidebar_title(
      'Buyer Information', null,
      array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
    );
  ?>
  <div class="span8" style="margin-left: 0;">
    <table class="table">
      <tr>
        <td style="width: 38%;">Name:</td>
        <td><?= $shopping_order->getShippingFullName(); ?></td>
      </tr>
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
            <?= $shopping_order->getShippingStateRegion(); ?>
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
  <div class="span4 send-pm">
    <?= form_tag('@messages_compose'); ?>
      <?= $pm_form->renderHiddenFields(); ?>
      <?= $pm_form['body']->render(array('style' => "width: 97%; height: 100px; margin-bottom: 0;")); ?>
      <button type="submit" class="btn-lightblue-normal textright" style="float: right; margin-top: 10px;">
        <i class="mail-icon-mini"></i> &nbsp;Send message
      </button>
    <?= '</form>'; ?>
  </div>

  <?php
    cq_sidebar_title(
      'Order Information', null,
      array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
    );
  ?>
  <div class="span8" style="margin-left: 0;">
    <table class="table">
      <tr>
        <td style="width: 38%;">Status:</td>
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
    </table>
  </div>

  <div class="span4">
    <table class="table">
      <tr>
        <td style="width: 50%;">Item Price:</td>
        <td>1 × <?= money_format('%.2n', (float) $shopping_order->getCollectiblesAmount()) ?></td>
      </tr>
      <tr>
        <td>Shipping Fee:</td>
        <td><?= money_format('%.2n', (float) $shopping_order->getShippingFeeAmount()) ?></td>
      </tr>
      <tr>
        <td>Total Amount:</td>
        <td><?= money_format('%.2n', (float) $shopping_order->getTotalAmount()) ?></td>
      </tr>
    </table>
  </div>

  <?php
    cq_sidebar_title(
      'PayPal Transaction', null,
      array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
    );
  ?>
  <table class="table">
    <tr>
      <td style="width: 25%;">Payment Status:</td>
      <td><?= strtoupper($shopping_payment->getStatus()); ?></td>
    </tr>
    <?php if ($v = $shopping_payment->getTransactionId()): ?>
    <tr>
      <td style="width: 25%;">Transaction ID:</td>
      <td><?= $v ?></td>
    </tr>
    <?php endif; ?>
    <?php if ($v = $shopping_payment->getSenderEmail()): ?>
    <tr>
      <td style="width: 25%;">Sender Email:</td>
      <td><?= $v; ?></td>
    </tr>
    <?php endif; ?>
  </table>
</div>
