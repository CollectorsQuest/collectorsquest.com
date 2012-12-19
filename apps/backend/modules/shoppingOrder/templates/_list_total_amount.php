<?php
/** @var $ShoppingOrder ShoppingOrder */

echo '<strong>', money_format('%.2n', (float) $ShoppingOrder->getCollectiblesAmount()), '</strong>',
     (0 != (int) $ShoppingOrder->getPromotionAmount()
       ? '<br/>-&nbsp;' . money_format('%.2n', (float) $ShoppingOrder->getPromotionAmount()) . '&nbsp;'
         . $ShoppingOrder->getSellerPromotion()->getPromotionName() : ''),
     (0 != (int) $ShoppingOrder->getTaxAmount()
       ? '<br/>+&nbsp;' . money_format('%.2n', $ShoppingOrder->getTaxAmount()) . '&nbsp;Tax' : ''),
     (0 != (int) $ShoppingOrder->getPromotionAmount()
       ? '<br/>+&nbsp;' . money_format('%.2n', (float) $ShoppingOrder->getShippingFeeAmount()) . '&nbsp;S&H' : '');
