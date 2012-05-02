<?php

class cqFrontendUser extends cqBaseUser
{

  const PRIVATE_MESSAGES_SENT_COUNT_KEY = 'private_messages_sent_count';

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
        $this->collector = new Collector();
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
    $q = ShoppingCartQuery::create();

    if ($this->isAuthenticated()) {
      $q->filterByCollector($this->getCollector());
    } else {
      $q->filterByCookieUuid($this->getCookieUuid());
    }

    if (!($shopping_cart = $q->findOne()) && $this->isAuthenticated())
    {
      if ($shopping_cart = ShoppingCartQuery::create()->findOneByCookieUuid($this->getCookieUuid()))
      {
        $shopping_cart->setCollector($this->getCollector());
        $shopping_cart->save();
      }
    }

    if (!$shopping_cart)
    {
      $shopping_cart = $q->findOneOrCreate();
      $shopping_cart->save();
    }

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

  /**
   * Set a cookie with the authenticated user's username
   *
   * @return    boolean
   */
  public function setUsernameCookie()
  {
    /** @var $response sfWebResponse */
    $response = sfContext::getInstance()->getResponse();

    if ($this->isAuthenticated())
    {
      // set username cookie
      $expiration_time = sfConfig::get('app_collector_username_cookie_expiration_age', 15 * 24 * 3600);
      $username_cookie = sfConfig::get('app_collector_username_cookie_name', 'cq_username');
      $response->setCookie($username_cookie, $this->getCollector()->getUsername(), time() + $expiration_time);

      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * Get the username of the last logged in user if the cookie has not yet expired
   *
   * @return    string
   */
  public function getUsernameFromCookie()
  {
    $username_cookie = sfConfig::get('app_collector_username_cookie_name', 'cqUsername');
    return sfContext::getInstance()->getRequest()->getCookie($username_cookie);
  }

  /**
   * Remove the username cookie
   */
  public function clearUsernameCookie()
  {
    // remove the username cookie
    $expiration_time = sfConfig::get('app_collector_username_cookie_expiration_age', 15 * 24 * 3600);
    $username_cookie = sfConfig::get('app_collector_username_cookie_name', 'cqUsername');
    sfContext::getInstance()->getResponse()->setCookie($username_cookie, '', time() - $expiration_time);
  }

  /**
   * Extended Authenticate method that will set or clear the username cookie
   * under the right conditions
   *
   * @param     boolean $boolean
   * @param     Collector $collector
   * @param     boolean $remember
   * @return    boolean
   *
   * @see       cqBaseUser::Authenticate()
   */
  public function Authenticate($boolean, Collector $collector = null, $remember = false)
  {
    $ret = parent::Authenticate($boolean, $collector, $remember);

    // if we are authenticating and the remember me option is not set we will
    // remember and autofill the username, so that the user will have
    // an easier time logging in next time
    if (true == $boolean && $collector instanceof Collector && false == $remember)
    {
      $this->setUsernameCookie();
    }

    // Clear the username cookie if the user manually logs out
    if (false == $boolean)
    {
      $this->clearUsernameCookie();
    }

    return $ret;
  }

}
