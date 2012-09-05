<?php /** @var $ShoppingOrder ShoppingOrder */ ?>

<?php if ($ShoppingPayment = $ShoppingOrder->getShoppingPayment()): ?>
  <?= ucfirst($ShoppingPayment->getStatus()); ?>
  <a href="javascript:void(0)" rel="clickover"
     data-original-title="<?= $ShoppingPayment->getProcessor() ?> Details" data-placement="top" data-width="300"
     data-content="<?php include_partial('popover_payment_details', array('ShoppingPayment' => $ShoppingPayment)) ?>">
     <?= $ShoppingPayment->getProcessor() ?>
  </a>
<?php endif; ?>
