<?php
/** @var $ShoppingOrder ShoppingOrder */

if ($ShoppingPayment = $ShoppingOrder->getShoppingPayment())
{
  echo ucfirst($ShoppingPayment->getStatus()) ;
  ?>
  <a  href="javascript:void(0)" rel="popover" data-original-title="<?= $ShoppingPayment->getProcessor() ?> Details
    <button class='close' type='button'>×</button>"
    data-content="
    <?php switch($ShoppingPayment->getProcessor()):
      case ('PayPal'): ?>
       <b>Transaction ID:</b> <?= $ShoppingPayment->getProperty(ShoppingPaymentPeer::PAYPAL_TRANSACTION_ID) ?><br />
       <b>Status:</b> <?= $ShoppingPayment->getProperty(ShoppingPaymentPeer::PAYPAL_STATUS) ?><br />
       <b>Sender Email:</b> <?= $ShoppingPayment->getProperty(ShoppingPaymentPeer::PAYPAL_SENDER_EMAIL) ?><br />
    <?php break;
      endswitch; ?>">
       <?= $ShoppingPayment->getProcessor() ?>
  </a>
  <?php
}
?>
