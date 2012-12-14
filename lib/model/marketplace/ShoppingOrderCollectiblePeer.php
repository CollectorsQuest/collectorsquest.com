<?php

require 'lib/model/marketplace/om/BaseShoppingOrderCollectiblePeer.php';

class ShoppingOrderCollectiblePeer extends BaseShoppingOrderCollectiblePeer
{

  /**
   * An array of fields that used generate array key and group ShoppingCartCollectibles[] to ShoppingOrders[]
   * @var        array
   */
  public static $group_fields = array(
    'SellerId',
    'PriceCurrency'
  );

}
