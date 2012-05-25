<?php

/**
 * @method  boolean  isOwnerOf($something)
 */
class cqLegacyUser extends cqBaseUser
{

  public function getLogoutUrl($next = null)
  {
    return '@logout?goto='. $next;
  }

  /**
   * Stubbed out getCollector method to comply with legacy expectation of returning
   * a collector with id of "-1" when the collector is no longer present in the DB
   *
   * @return    Collector
   */
  public function getCollector()
  {
    if (!($this->collector instanceof Collector))
    {
      if ($this->collector === null && ($this->getAttribute("id", null, "collector") !== null))
      {
        $this->collector = CollectorPeer::retrieveByPK($this->getAttribute("id", null, "collector"));
      }
      else
      {
        $this->collector = new Collector();
        $this->collector->setId(-1);
      }
    }
    else if ($this->collector->getId() == -1 && $this->getAttribute("id", null, "collector") !== null)
    {
      $this->collector = CollectorPeer::retrieveByPK($this->getAttribute("id", null, "collector"));
    }

    return $this->collector;
  }

  public function isSeller()
  {
    return 'seller' === $this->getAttribute('user_type', 'collector', 'collector');
  }

  public function getShoppingCart()
  {
    /** @var $q ShoppingCartQuery */
    $q = ShoppingCartQuery::create()
       ->filterByCollector($this->getCollector())
       ->filterBySessionId($this->isAuthenticated() ? null : session_id());

    /** @var $shopping_cart ShoppingCart */
    $shopping_cart = $q->findOneOrCreate();
    $shopping_cart->save();

    return $shopping_cart;
  }

  public function getShoppingCartCollectiblesCount()
  {
    // We default to zero collectibles in the cart
    $count = 0;

    if ($shopping_cart = $this->getShoppingCart())
    {
      $count = $shopping_cart->countShoppingCartCollectibles();
    }

    return $count;
  }

}
