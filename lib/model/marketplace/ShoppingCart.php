<?php

require 'lib/model/marketplace/om/BaseShoppingCart.php';

class ShoppingCart extends BaseShoppingCart
{

  /**
   * Return a ShoppingCartCollectible associated with this shopping cart by its
   * collectible id
   *
   * @param     integer $collectible_id
   * @param     PropelPDO $con
   *
   * @return    ShoppingCartCollectible
   */
  public function getShoppingCartCollectibleById($collectible_id, PropelPDO $con = null)
  {
    return ShoppingCartCollectibleQuery::create()
      ->findPK(array($this->getId(), $collectible_id));
  }

}
