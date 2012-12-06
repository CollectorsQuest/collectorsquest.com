<?php /** @var $ShoppingOrder ShoppingOrder */ ?>

<?php if ($ShoppingPayment = $ShoppingOrder->getShoppingPayment()): ?>

  <?= ucfirst($ShoppingPayment->getStatus()); ?>

  <?php if ($ShoppingPayment->getStatus() == ShoppingPaymentPeer::STATUS_COMPLETED) { ?>
    <a href="javascript:void(0)" rel="clickover"
       data-original-title="<?= $ShoppingPayment->getProcessor() ?> Details" data-placement="top" data-width="300"
       data-content="<?php include_partial('popover_payment_details', array('ShoppingPayment' => $ShoppingPayment)) ?>">
       <?= $ShoppingPayment->getProcessor() ?>
    </a>
  <?php
    }
    elseif ($ShoppingPayment->getStatus() == ShoppingPaymentPeer::STATUS_CONFIRMED)
    {
      if ($ShoppingPayment->getUpdatedAt('U') < time() - 86400)
      {
        echo '<br/><span style="color: red;">timed out</span>';
      }
    }
    else
    {
      if ($ShoppingPayment->getUpdatedAt('U') < time() - 3600)
      {
        echo '<br/><span style="color: red;">abandoned</span>';
      }
    };
  ?>
<?php endif; ?>
