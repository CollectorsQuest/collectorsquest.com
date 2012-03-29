<?php

class cqFrontendUser extends cqBaseUser
{

  /**
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
        $this->collector = new Collector();;
      }
    }
    else if ($this->collector->getId() == null && $this->getAttribute("id", null, "collector") !== null)
    {
      $this->collector = CollectorPeer::retrieveByPK($this->getAttribute("id", null, "collector"));
    }

    return $this->collector;
  }

  public function getShoppingCart()
  {
    $q = ShoppingCartQuery::create()
       ->filterByCollector($this->getCollector())
       ->filterBySessionId($this->isAuthenticated() ? null : session_id());

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
