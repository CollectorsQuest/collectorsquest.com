<?php

class cqFrontendUser extends cqBaseUser
{

  /** @var integer */
  protected $unread_messages_count;

  const PRIVATE_MESSAGES_SENT_COUNT_KEY = 'private_messages_sent_count';
  const DROPBOX_OPEN_STATE_COOKIE_NAME = 'cq_mycq_dropbox_open';

  /**
   * @param  boolean  $strict
   * @return null|Collector
   */
  public function getCollector($strict = false)
  {
    if (!($this->collector instanceof Collector))
    {
      if ($this->collector === null && ($this->getAttribute("id", null, "collector") !== null))
      {
        $this->collector = CollectorPeer::retrieveByPK($this->getAttribute("id", null, "collector"));

        if (!$this->collector)
        {
          // the user does not exist anymore in the database
          $this->Authenticate(false);

          throw new sfException('The collector does not exist anymore in the database.');
        }
      }
      else if ($strict === false)
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

  /**
   * Return the country for the currently logged in user, or try to get it from the
   * user's IP
   *
   * If not possible to retrieve by IP the value of the $default
   * param will be returned
   *
   * @param     string $default
   * @return    string|false
   */
  public function getCountryCode($default = false)
  {
    if ( $this->isAuthenticated() && !$this->getCollector()->isNew()
      && $country_code = $this->getCollector()->getProfile()->getCountryIso3166() )
    {
      return $country_code;
    }

    return cqStatic::getGeoIpCountryCode(sfContext::getInstance()->getRequest()->getRemoteAddress(), true) ?: $default;
  }

  /**
   * Return the current user's country name
   *
   * @param     type $country_code
   * @return    string|false
   *
   * @see       cqFrontendUser::getCountryCode()
   */
  public function getCountryName($country_code = null)
  {
    if (null === $country_code)
    {
      $country_code = $this->getCountryCode();
    }

    return GeoCountryQuery::create()
      ->filterByIso3166($country_code)
      ->select('Name')
      ->findOne() ?: false;
  }

  /**
   * @param  boolean  $strict
   * @return null|Seller
   */
  public function getSeller($strict = false)
  {
    $seller = null;

    if (($collector = $this->getCollector($strict)) && $collector->getIsSeller())
    {
      $seller = new Seller();
      $collector->copyInto($seller, false, false);
      $seller->setId($collector->getId());
    }

    return $seller;
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
   * Set whether the mycq dropbox component should be opened or closed
   * on page load
   *
   * @param     boolean $is_open
   * @return    cqFrontendUser
   */
  public function setMycqDropboxOpenState($is_open)
  {
    sfContext::getInstance()->getResponse()->setCookie(
      self::DROPBOX_OPEN_STATE_COOKIE_NAME,
      // boolean value
      !!$is_open,
      // 10 years
      time() + 60 * 60 * 24 * 365 * 10
    );

    return $this;
  }

  /**
   * Get the current setting for the mycq dropbox component, where all newly
   * uploaded files are listed.
   *
   * Returns the value of the mycq dropbox open state cookie, true by default
   *
   * @return    boolean
   */
  public function getMycqDropboxOpenState()
  {
    // Respect the cookie only in selected areas of My CQ
    if (in_array(SmartMenu::getSelected('mycq_menu'), array('collections')))
    {
      return !!sfContext::getInstance()->getRequest()->getCookie(
        self::DROPBOX_OPEN_STATE_COOKIE_NAME, true);
    }
    else
    {
      return false;
    }
  }

  /**
   * Clear the mycql dropbox open state cookie
   */
  public function clearMycqDropboxOpenStateCookie()
  {
    sfContext::getInstance()->getResponse()->setCookie(
      self::DROPBOX_OPEN_STATE_COOKIE_NAME,
      '',
      // 10 years
      time() - 60 * 60 * 24 * 365 * 10
    );
  }

  /**
   * Get the username of the last logged in user if the cookie has not yet expired
   *
   * @return    string
   */
  public function getUsernameFromCookie()
  {
    $username_cookie = sfConfig::get('app_collector_username_cookie_name', 'cq_username');
    return sfContext::getInstance()->getRequest()->getCookie($username_cookie);
  }

  /**
   * Remove the username cookie
   */
  public function clearUsernameCookie()
  {
    // remove the username cookie
    $expiration_time = sfConfig::get('app_collector_username_cookie_expiration_age', 15 * 24 * 3600);
    $username_cookie = sfConfig::get('app_collector_username_cookie_name', 'cq_username');
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
      $this->clearMycqDropboxOpenStateCookie();
    }

    return $ret;
  }

  /**
   * Retrieve the unread messages count, or null for unauthenticated users
   *
   * @return    integer|null
   */
  public function getUnreadMessagesCount()
  {
    if (null === $this->unread_messages_count && $this->isAuthenticated())
    {
      $this->unread_messages_count = PrivateMessageQuery::create()
        ->filterByCollectorRelatedByReceiver($this->getCollector())
        ->filterByIsRead(false)
        ->count();
    }

    return $this->unread_messages_count;
  }

  /**
   * This will be executed before new user (Collector) is created
   */
  public function preCreateHook()
  {
    // nothing yet
  }

  /**
   * This will be executed after new user (Collector) is created
   */
  public function postCreateHook($collector = null)
  {
    /** @var $collector Collector */
    $collector = $collector ?: $this->getCollector();

    // We cannot do anything without a Collector
    if (!($collector instanceof Collector) || $collector->isNew()) {
      return false;
    }

    // Assign a random Avatar
    $collector->assignRandomAvatar();

    // Check if the signup is after a shopping order(s)
    if ($shopping_order_uuids = $this->getAttribute('orders', null, 'shopping'))
    {
      foreach ($shopping_order_uuids as $uuid)
      {
        if ($shopping_order = ShoppingOrderQuery::create()->findOneByUuid($uuid))
        {
          $shopping_order->setCollectorId($collector->getId());
          $shopping_order->save();
        }
      }

      $this->setAttribute('orders', null, 'shopping');
    }

    if ($_shipping_address = $this->getAttribute('shipping_address', null, 'shopping'))
    {
      if (empty($_shipping_address['address_id']))
      {
        $shipping_address = new CollectorAddress();
        $shipping_address->fromArray($_shipping_address, BasePeer::TYPE_FIELDNAME);
        $shipping_address->setCollector($collector);
        $shipping_address->save();

        $this->setAttribute('shipping_address', null, 'shopping');
      }
    }

    // Finally, send the welcome email
    $cqEmail = new cqEmail(sfContext::getInstance()->getMailer());
    $cqEmail->send($collector->getUserType() . '/welcome_to_cq', array(
      'to' => $collector->getEmail(),
    ));
  }

}
