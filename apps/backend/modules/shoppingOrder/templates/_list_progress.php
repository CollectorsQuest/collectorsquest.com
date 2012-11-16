<?php

/* @var $ShoppingOrder ShoppingOrder */
switch ($ShoppingOrder->getProgress())
{
  case ShoppingOrderPeer::PROGRESS_STEP1:
    echo 'Shipping';
    if ($ShoppingOrder->getUpdatedAt() < time() - 86400)
    {
      echo '<br/><span style="color: red;">abandoned</span>';
    }
    break;
  case ShoppingOrderPeer::PROGRESS_STEP2:
    echo 'Payment';
    if ($ShoppingOrder->getUpdatedAt() < time() - 86400)
    {
      echo '<br/><span style="color: red;">abandoned</span>';
    }
    break;
  case ShoppingOrderPeer::PROGRESS_STEP3:
    echo 'Sale';
    break;
}
