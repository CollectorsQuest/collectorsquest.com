<?php


require 'lib/model/marketplace/om/BaseShoppingOrderCollectiblePeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'shopping_order_collectible' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.marketplace
 */
class ShoppingOrderCollectiblePeer extends BaseShoppingOrderCollectiblePeer {

  /**
   * An array of fields that used generate array key and group ShoppingCartCollectibles[] to ShoppingOrders[]
   * @var        array
   */
  public static $group_fields = array(
    'SellerId',
    'PriceCurrency'
  );


}
