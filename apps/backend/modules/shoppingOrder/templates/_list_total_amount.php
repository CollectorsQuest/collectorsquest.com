<?php
/** @var $ShoppingOrder ShoppingOrder */

echo '<strong>', money_format('%.2n', (float) $ShoppingOrder->getCollectiblesAmount()), '</strong>',
     (0 != $ShoppingOrder->getPromotionAmount('integer')
       ? '<br/>-&nbsp;' . money_format('%.2n', (float) $ShoppingOrder->getPromotionAmount()) . '&nbsp;'
         . $ShoppingOrder->getSellerPromotion()->getPromotionName() : ''),
     (0 != $ShoppingOrder->getTaxAmount('integer')
       ? '<br/>+&nbsp;' . money_format('%.2n', $ShoppingOrder->getTaxAmount()) . '&nbsp;Tax' : ''),
     (0 != $ShoppingOrder->getShippingFeeAmount('integer')
       ? '<br/>+&nbsp;' . money_format('%.2n', (float) $ShoppingOrder->getShippingFeeAmount()) . '&nbsp;S&H' : '');
