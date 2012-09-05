<?php
/** @var $ShoppingOrder ShoppingOrder */

echo money_format('%.2n', (float) $ShoppingOrder->getTotalAmount());
