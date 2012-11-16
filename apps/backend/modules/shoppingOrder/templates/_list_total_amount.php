<?php
/** @var $ShoppingOrder ShoppingOrder */

echo '<strong>', money_format('%.2n', (float) $ShoppingOrder->getCollectiblesAmount()), '</strong>',
     '<br/>+&nbsp;', money_format('%.2n', (float) $ShoppingOrder->getShippingFeeAmount()), '&nbsp;S&H';
