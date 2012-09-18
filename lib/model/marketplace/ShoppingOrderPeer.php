<?php

require 'lib/model/marketplace/om/BaseShoppingOrderPeer.php';

class ShoppingOrderPeer extends BaseShoppingOrderPeer
{
  /**
   * WARNING: The method has a serious limitation when used with small numbers
   *          The logic here is based on $id of at least >= 1000001
   *
   * @param  integer  $id
   * @param  integer  $length
   *
   * @return null|string
   */
  public static function getUuidFromId($id, $length = 8)
  {
    if (empty($id)) {
      return null;
    }

    $key = 'QKISTMLVYFFKZIMFYTTU';

    // first entry in array is length of $key
    $fudgefactor[] = strlen($key);

    $tot = 0;
    for ($i = 0; $i < strlen($key); $i++)
    {
      // extract a (multibyte) character from $key
      $char = substr($key, $i, 1);

      // Identify its position in $scramble1
      $num = strpos('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', $char);
      if ($num === false) {
        return null;
      }

      $fudgefactor[] = $num;
      $tot = $tot + $num;
    }

    // insert total as last entry in array
    $fudgefactor[] = $tot;

    // pad $id with spaces up to $idlen
    $id = str_pad($id, $length, STR_PAD_LEFT);

    $target = null;
    $factor2 = 0;

    for ($i = 0; $i < strlen($id); $i++)
    {
      $char1 = substr($id, $i, 1);

      // identify its position in $scramble1
      $num1 = strpos('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', $char1);

      // get an adjustment value using $fudgefactor
      $adj = array_shift($fudgefactor);
      $adj = $adj + 1.75;
      $fudgefactor[] = $adj;

      if ($adj % 3 == 0) {
        $adj = $adj * -1;
      }

      $factor1 = $factor2 + $adj;                 // accumulate in $factor1
      $num2    = round(round($factor1) + $num1);  // generate offset for $scramble2

      $limit = strlen('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');

      while ($num2 >= $limit) {
        $num2 = $num2 - $limit;   // value too high, so reduce it
      } // while
      while ($num2 < 0) {
        $num2 = $num2 + $limit;   // value too low, so increase it
      }

      $factor2 = $factor1 + $num2;
      $char2 = substr('UKAH652LMOQFBDIEG03JT17N4C89XPVWRSYZ', $num2, 1);

      // append to $target string
      $target .= $char2;
    }

    return $target;
  }

  /**
   * @param     string $hash
   * @param     PropelPDO $con
   * @return    ShoppingOrder|null
   */
  public static function retrieveByHash($hash, PropelPDO $con = null)
  {
    if (!empty($hash))
    {
      // Split the Hash parts
      @list($version, $id, $hmac, $time) = explode(';', $hash);

      // Try to get the ShoppingOrder object
      if (( $shopping_order = self::retrieveByPk($id, $con) ))
      {
        // Finally check if the $hash is valid
        return $shopping_order->getHash($version, $time) === $hash ? $shopping_order : null;
      }
    }

    return null;
  }

  /**
   * convert $key into an array of numbers
   *
   * @static
   *
   * @param  string  $key
   * @return array
   */
  private static function _convertKey($key)
  {
    if (empty($key)) {
      return array();
    }

    // first entry in array is length of $key
    $array[] = strlen($key);

    $tot = 0;
    for ($i = 0; $i < strlen($key); $i++)
    {
      // extract a (multibyte) character from $key
      $char = substr($key, $i, 1);

      // Identify its position in $scramble1
      $num = strpos('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', $char);
      if ($num === false) {
        return array();
      }

      $array[] = $num;
      $tot = $tot + $num;
    }

    // insert total as last entry in array
    $array[] = $tot;

    return $array;
  }

  /**
   * build grouped array of ShoppingOrder|ShoppingCartCollectible
   *
   * @param ShoppingCart $shopping_cart
   * @param bool $return_cart_collectibles
   * @return ShoppingOrder[]|ShoppingCartCollectible[]
   */
  public static function cartToOrders(ShoppingCart $shopping_cart, $return_cart_collectibles = false)
  {
    $shopping_orders = array();
    foreach ($shopping_cart->getShoppingCartCollectibles() as $cart_collectible)
    {
      /** @var $shopping_order_collectible ShoppingOrderCollectible */
      $shopping_order_collectible = $cart_collectible->getShoppingOrderCollectible();
      $key = $shopping_order_collectible->getGroupKey();
      if ($return_cart_collectibles)
      {
        $shopping_orders[$key][] = $cart_collectible;
      }
      else
      {
        if (!isset($shopping_orders[$key]))
        {
          $shopping_orders[$key] = new ShoppingOrder();
          $shopping_orders[$key]->setShippingCountryIso3166($cart_collectible->getShippingCountryIso3166());
        }
        $shopping_orders[$key]->addShoppingOrderCollectible($shopping_order_collectible);
      }

    }
    return $shopping_orders;
  }
}
