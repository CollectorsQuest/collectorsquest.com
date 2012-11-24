<?php

class cqFrontendUser extends cqBaseUser
{

  /* @var integer */
  protected $unread_messages_count;

  /* @var array */
  protected $visitor_info_array;

  /* @var integer */
  protected $_sf_guard_user_id = null;

  /**
   * Sent counters for comments and private messages; Used for spam thresholds
   */
  const SENT_COUNT_COMMENTS = 'sent_count_comments';
  const SENT_COUNT_PRIVATE_MESSAGES = 'sent_count_private_messages';

  protected static $sent_counter_keys = array(
    self::SENT_COUNT_COMMENTS,
    self::SENT_COUNT_PRIVATE_MESSAGES,
  );

  /**
   * A sent counter should be incremented only once per request,
   * we keep a list of the incremented counters here
   */
  protected $sent_count_incemented = array();

  const PRIVATE_MESSAGES_SENT_TEXT = 'text_to_check_similarity';

  /**
   * Cookie names
   */
  const DROPBOX_OPEN_STATE_COOKIE_NAME = 'cq_mycq_dropbox_open';
  const VISITOR_INFO_COOKIE_NAME = 'cq_visitor';

  public function __construct(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::__construct($dispatcher, $storage, $options);

    if ($this->isAdmin())
    {
      // Connect listeners
      $dispatcher->connect('application.show_object', array('cqAdminBar', 'listenShowObject'));

      /* @var $response sfWebResponse */
      $response = sfContext::getInstance()->getResponse();

      // Add the adminbar.css only when the use is admin
      $response->addStylesheet('frontend/modules/adminbar.css');
    }
  }

  /**
   * @param  boolean  $strict
   *
   * @throws sfException
   * @return null|Collector
   */
  public function getCollector($strict = false)
  {
    // If the user is not authenticated return null immediately
    if (!$this->isAuthenticated())
    {
      if (null !== $this->getAttribute('id', null, 'collector'))
      {
        $this->Authenticate(false);
      }

      return $this->collector = ($strict === true) ? null: new Collector();
    }

    if (!($this->collector instanceof Collector))
    {
      if ($this->collector === null && ($this->getAttribute('id', null, 'collector') !== null))
      {
        $this->collector = CollectorPeer::retrieveByPK(
          $this->getAttribute('id', null, 'collector')
        );

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
    else if ($this->collector->getId() == null && $this->getAttribute('id', null, 'collector') !== null)
    {
      $this->collector = CollectorPeer::retrieveByPK($this->getAttribute('id', null, 'collector'));
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
   * @param     boolean|string $default
   * @return    boolean|string
   */
  public function getCountryCode($default = false)
  {
    if ($this->isAuthenticated() && !$this->getCollector()->isNew()
      && $country_code = $this->getCollector()->getProfile()->getCountryIso3166()
    )
    {
      return $country_code;
    }

    // Get the IP address of the request
    $ip_address = cqContext::getInstance()->getRequest()->getRemoteAddress();

    return cqStatic::getGeoIpCountryCode($ip_address, true) ?: $default;
  }

  /**
   * Return the current user's country name
   *
   * @param     string $country_code
   * @return    boolean|string
   *
   * @see       cqFrontendUser::getCountryCode()
   */
  public function getCountryName($country_code = null)
  {
    if (null === $country_code)
    {
      $country_code = $this->getCountryCode();
    }

    return iceModelGeoCountryQuery::create()
      ->filterByIso3166($country_code)
      ->select('Name')
      ->findOne() ?: false;
  }

  /**
   * @param     boolean  $strict
   * @return    null|Seller
   */
  public function getSeller($strict = false)
  {
    if (($collector = $this->getCollector($strict)))
    {
      return $collector->getSeller();
    }

    return null;
  }

  public function getShoppingCart()
  {
    $q = ShoppingCartQuery::create();

    if ($this->isAuthenticated())
    {
      $q->filterByCollector($this->getCollector());
    }
    else
    {
      $q->filterByCollectorId(null, Criteria::EQUAL);
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
    $response = cqContext::getInstance()->getResponse();

    if ($this->isAuthenticated())
    {
      // set username cookie
      $expiration_time = sfConfig::get('app_collector_username_cookie_expiration_age', 15 * 24 * 3600);
      $username_cookie = sfConfig::get('app_collector_username_cookie_name', 'cq_username');
      $response->setCookie(
        $username_cookie, urlencode($this->getCollector()->getUsername()), time() + $expiration_time
      );

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
    cqContext::getInstance()->getResponse()->setCookie(
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
    /**
     * Check if we have requested a different state
     * than the one defined in the cookie
     */
    if ($this->hasFlash('cq_mycq_dropbox_open', 'cookies'))
    {
      return (boolean) $this->getFlashAndDelete('cq_mycq_dropbox_open', false, 'cookies');
    }
    else
    {
      return (boolean) cqContext::getInstance()->getRequest()->getCookie(
        self::DROPBOX_OPEN_STATE_COOKIE_NAME, true
      );
    }
  }

  /**
   * Clear the mycql dropbox open state cookie
   */
  public function clearMycqDropboxOpenStateCookie()
  {
    cqContext::getInstance()->getResponse()->setCookie(
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
    return urldecode(cqContext::getInstance()->getRequest()->getCookie($username_cookie));
  }

  /**
   * Remove the username cookie
   */
  public function clearUsernameCookie()
  {
    // remove the username cookie
    $expiration_time = sfConfig::get('app_collector_username_cookie_expiration_age', 15 * 24 * 3600);
    $username_cookie = sfConfig::get('app_collector_username_cookie_name', 'cq_username');
    cqContext::getInstance()->getResponse()->setCookie($username_cookie, '', time() - $expiration_time);
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

    // when we log in user add $_SESSION variables used in blog
    if (true == $boolean)
    {
      $collector = $this->getCollector();
      $this->setAttribute('id', $collector->getId(), 'collector');
      $this->setAttribute('email', $collector->getEmail(), 'collector');
      $this->setAttribute('display_name', $collector->getDisplayName(), 'collector');
      $this->setAttribute('slug', $collector->getSlug(), 'collector');
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
  public function postCreateHook($collector = null, $send_email = true)
  {
    /** @var $collector Collector */
    $collector = $collector ?: $this->getCollector();

    // We cannot do anything without a Collector
    if (!($collector instanceof Collector) || $collector->isNew())
    {
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

    // Finally, send the welcome email if requested
    // NOTE: Currently it is disabled on purpose
    if (false && $send_email === true)
    {
      $collector_email = CollectorEmailPeer::retrieveByCollectorEmail(
        $collector, $collector->getEmail(), false
      );

      // Only send the email if the email is not verified
      if ($collector_email)
      {
        $cqEmail = new cqEmail(cqContext::getInstance()->getMailer());
        $cqEmail->send($collector->getUserType() . '/welcome_verify_email', array(
          'to'     => $collector->getEmail(),
          'params' => array(
            'collector'       => $collector,
            'collector_email' => $collector_email,
          )
        ));
      }
    }

    // Defensio request
    $collector->sendToDefensio('UPDATE');

    return true;
  }

  /**
   * @param  BaseObject $something
   * @return boolean
   */
  public function isOwnerOf($something)
  {
    $is_owner = parent::isOwnerOf($something);

    if (!$is_owner && method_exists($something, 'getId'))
    {
      $is_owner = in_array($something->getId(), $this->getObjectIdsOwned(get_class($something)));
    }

    return $is_owner;
  }

  /**
   * @param object $object
   * @return boolean
   */
  public function setOwnerOf($object)
  {
    if (!is_object($object) || !method_exists($object, 'getId'))
    {
      return false;
    }

    $name = sfInflector::underscore(get_class($object));
    $ids = $this->getAttribute($name, array(), 'cq/user/owns');
    $ids[] = $object->getId();

    return $this->setAttribute($name, array_unique($ids), 'cq/user/owns');
  }

  public function getObjectsOwned($name)
  {
    $ids = $this->getObjectIdsOwned($name);

    return call_user_func(array(sfInflector::classify($name) . 'Peer', 'retrieveByPks'), $ids);
  }

  public function getObjectIdsOwned($name)
  {
    $name = sfInflector::underscore($name);
    $ids = $this->getAttribute($name, array(), 'cq/user/owns');

    return $ids;
  }

  /**
   * Get the collector corresponding to the UUID stored in the cq_uuid cookie
   *
   * @return    Collector|null
   */
  public function getCollectorByUuid()
  {
    return $this->getCookieUuid()
      ? CollectorQuery::create()
         ->filterByCookieUuid($this->getCookieUuid())
        ->findOne()
      : null;
  }

  /**
   * Get all visitor info properties as array
   *
   * @param     boolean $force
   * @return    array
   *
   * @see       CollectorPeer::$visitor_info_props
   */
  public function getVisitorInfoArray($force = false)
  {
    if (null === $this->visitor_info_array || $force)
    {
      // if the user is authenticated or we can get it from the UUID cookie
      $collector = $this->getCollector($strict = true) ?: $this->getCollectorByUuid();

      if ($collector)
      {
        // populate the array from ExtraProperties behavior data for the collector
        $this->visitor_info_array = array();

        foreach (CollectorPeer::$visitor_info_props as $prop_name)
        {
          $this->visitor_info_array[$prop_name] = $collector->getProperty($prop_name);
        }
      }
      else
      {
        $this->visitor_info_array = $this->getVisitorInfoCookieData();
      }
    }

    return $this->visitor_info_array;
  }

  /**
   * Persist all visitor info properties,
   * either to a cookie or to Collector ExtraProperty
   *
   * @param     array $data
   * @return    cqFrontendUser
   *
   * @see       CollectorPeer::$visitor_info_props
   */
  public function setVisitorInfoArray($data)
  {
    $this->visitor_info_array = $data;

    // if the user is authenticated or we can get it from the UUID cookie
    $collector = $this->getCollector($strict = true) ?: $this->getCollectorByUuid();

    if ($collector)
    {
      // populate ExtraProperties behavior data for the collector
      foreach ($data as $prop_name => $value)
      {
        // We do not want to reset this property if it already exists
        if (
          $prop_name === CollectorPeer::PROPERTY_VISITOR_INFO_FIRST_VISIT_AT &&
          $collector->getProperty($prop_name)
        )
        {
          continue;
        }

        $collector->setProperty($prop_name, $value);
      }

      $collector->save();
    }
    else
    {
      $this->setVisitorInfoCookieData($data);
    }

    return $this;
  }

  /**
   * Get a visitor info property by its key
   *
   * @param     string $prop_name
   * @param     mixed $default
   *
   * @return    mixed
   * @throws    RuntimeException
   *
   * @see       CollectorPeer::$visitor_info_props
   */
  public function getVisitorInfo($prop_name, $default = null)
  {
    $data = $this->getVisitorInfoArray();

    if (!array_key_exists($prop_name, (array) $data))
    {
      throw new RuntimeException(sprintf(
        '[cqFrontendUser] There is no visitor property named %s',
        $prop_name));
    }

    return $data[$prop_name] !== null
      ? $data[$prop_name]
      : $default;
  }

  /**
   * Persist a visitor info property,
   * either in a cookie or in Collector ExtraProperty
   *
   * @param     string $prop_name
   * @param     mixed $value
   *
   * @return    cqFrontendUser
   * @throws    RuntimeException
   *
   * @see       CollectorPeer::$visitor_info_props
   */
  public function setVisitorInfo($prop_name, $value)
  {
    if (!in_array($prop_name, CollectorPeer::$visitor_info_props))
    {
      throw new RuntimeException(sprintf(
        '[cqFrontendUser] Unknown visitor info key %s allowed keys: %s',
        $prop_name, implode(', ', CollectorPeer::$visitor_info_props)));
    }

    $data = $this->getVisitorInfoArray();
    $data[$prop_name] = $value;

    $this->setVisitorInfoArray($data);

    return $this;
  }

  /**
   * Internal method
   *
   * @param     array $data
   */
  protected function setVisitorInfoCookieData($data)
  {
    /** @var $response sfWebResponse */
    $response = cqContext::getInstance()->getResponse();
    $response->setCookie(
      self::VISITOR_INFO_COOKIE_NAME,
      base64_encode(json_encode($data)),
      strtotime('+ 1 year'),
      '/'
    );
  }

  /**
   * Internal method
   *
   * @return    array
   */
  public function getVisitorInfoCookieData()
  {
    $default_data = array_combine(
      CollectorPeer::$visitor_info_props,
      array_fill(0, count(CollectorPeer::$visitor_info_props), null)
    );

    /** @var $request sfWebRequest */
    $request = cqContext::getInstance()->getRequest();
    $raw_data = $request->getCookie(self::VISITOR_INFO_COOKIE_NAME, null);

    if (!$raw_data)
    {
      return $default_data;
    }

    return (array) json_decode(base64_decode($raw_data), true);
  }

  /**
   * Get the sent count for a specific counter
   *
   * @param     string $key
   * @return    integer
   */
  public function getSentCount($key)
  {
    $this->checkSentCounterKey($key);

    return $this->getAttribute($key, 0, 'collector');
  }

  /**
   * Reset the sent count for a specific counter, or manually set it
   *
   * @param     string  $key
   * @param     mixed  $value
   *
   * @return    cqFrontendUser
   */
  public function resetSentCount($key, $value = 0)
  {
    $this->checkSentCounterKey($key);
    $this->setAttribute($key, $value, 'collector');

    return $this;
  }

  /**
   * Increments a specific sent counter; Will only increment the counter once
   * per request, even if called multiple times
   *
   * @param     string $key
   * @return    cqFrontendUser
   */
  public function incrementSentCount($key)
  {
    $this->checkSentCounterKey($key);

    if (!isset($this->sent_count_incemented[$key]))
    {
      $this->resetSentCount($key, $this->getSentCount($key) + 1);
      $this->sent_count_incemented[$key] = true;
    }

    return $this;
  }

  /**
   * @param     string $key
   * @throws    RuntimeException
   *
   * @return    void
   */
  protected function checkSentCounterKey($key)
  {
    if (!in_array($key, self::$sent_counter_keys))
    {
      throw new RuntimeException(sprintf('Unknown sent counter %s', $key));
    }
  }

  /**
   * Try to delete the related collector; Returns true on successful deletion
   * and false when the collector was already removed or never committed to teh DB
   *
   * @return    boolean
   */
  public function delete()
  {
    try
    {
      $collector = $this->getCollector(true);
      $this->Authenticate(false);
      $collector->delete();
    }
    catch (Exception $e)
    {
      return false;
    }

    return true;
  }

  /**
   * Check, is current user logged in at backend as admin
   *
   * @return bool
   */
  public function isAdmin()
  {
    /* @var $request sfWebRequest */
    $request = sfContext::getInstance()->getRequest();

    /* @var $cq_admin_cookie string */
    $cq_admin_cookie = sfConfig::get('app_frontend_admin_cookie_name', 'cq_admin');

    if ($cookie = $request->getCookie($cq_admin_cookie))
    {
      @list($id, $hmac) = explode(':', $cookie);

      if ((string) $hmac === hash_hmac('sha1', $id . ':' . $_SERVER['REMOTE_ADDR'], $this->getCookieUuid()))
      {
        $this->_sf_guard_user_id = $id;

        return true;
      }
    }

    return false;
  }

  public function getBackendUserId()
  {
    return $this->isAdmin() ? $this->_sf_guard_user_id: null;
  }

}
