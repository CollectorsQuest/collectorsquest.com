<?php

switch($ShoppingPayment->getProcessor())
{
  case ('PayPal'):
    echo '<b>Transaction ID:</b> ', $ShoppingPayment->getProperty(ShoppingPaymentPeer::PAYPAL_TRANSACTION_ID), '<br />';
    echo '<b>Status:</b> ', $ShoppingPayment->getProperty(ShoppingPaymentPeer::PAYPAL_STATUS), '<br />';
    echo '<b>Sender Email:</b> ', $ShoppingPayment->getProperty(ShoppingPaymentPeer::PAYPAL_SENDER_EMAIL), '<br />';
    break;
}
