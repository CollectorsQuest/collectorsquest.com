<?php

/**
 * @method  boolean  isOwnerOf($something)
 */
class cqBaseUser extends IceSecurityUser
{
  /** @var Collector */
  private $collector = null;

  public function __construct(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::__construct($dispatcher, $storage, $options);

    self::$_facebook_data = $this->getAttribute('data', null, 'icepique/user/facebook');
  }

  /**
   * @return integer
   */
  public function getId()
  {
    if ($this->isAuthenticated() && ($collector = $this->getCollector()))
    {
      return $collector->getId();
    }

    return 0;
  }

  /**
   * @return string
   */
  public function getSlug()
  {
    if ($this->isAuthenticated() && ($collector = $this->getCollector()))
    {
      return $collector->getSlug();
    }

    return 'n-a';
  }

  /**
   * @param  boolean  $boolean
   * @param  Collector|null $collector
   * @param  boolean  $remember
   *
   * @return boolean
   */
  public function Authenticate($boolean, Collector $collector = null, $remember = false)
  {
    $this->clearAttributes();
    $this->clearCredentials();

    if ($collector === null)
    {
      $collector = $this->getCollector();
    }

    if ($boolean == false)
    {
      $this->setAuthenticated(false);
      $this->collector = null;

      // remove remember me cookie
      $expiration_age = sfConfig::get('app_collector_remember_expiration_age', 15 * 24 * 3600);
      $remember_cookie = sfConfig::get('app_collector_remember_cookie_name', 'cqRemember');
      sfContext::getInstance()->getResponse()->setCookie($remember_cookie, '', time() - $expiration_age);
    }
    else if ($collector instanceof Collector && $boolean == true)
    {
      $this->addCredential(strtolower($collector->getUserType()));

      $this->setAttribute('id', $collector->getId(), 'collector');
      $this->setAttribute('username', $collector->getUsername(), 'collector');
      $this->setAttribute('email', $collector->getEmail(), 'collector');
      $this->setAttribute('user_type', $collector->getUserType(), 'collector');

      // remember?
      if ($remember)
      {
        // remove old keys
        $c = new Criteria();
        $expiration_age = sfConfig::get('app_collector_remember_expiration_age', 15 * 24 * 3600);
        $c->add(CollectorRememberKeyPeer::CREATED_AT, time() - $expiration_age, Criteria::LESS_THAN);
        CollectorRememberKeyPeer::doDelete($c, $con);

        // remove other keys from this user
        $c = new Criteria();
        $c->add(CollectorRememberKeyPeer::COLLECTOR_ID, $collector->getId());
        CollectorRememberKeyPeer::doDelete($c, $con);

        // generate new keys
        $key = $this->generateRandomKey();

        // save key
        $rk = new CollectorRememberKey();
        $rk->setRememberKey($key);
        $rk->setCollector($collector);
        $rk->setIpAddress($_SERVER['REMOTE_ADDR']);
        $rk->save($con);

        // make key as a cookie
        $remember_cookie = sfConfig::get('app_collector_remember_cookie_name', 'cqRemember');
        sfContext::getInstance()->getResponse()->setCookie($remember_cookie, $key, time() + $expiration_age);
      }

      $this->collector = $collector;
      $this->setAuthenticated(true);
    }

    if ($collector instanceof Collector && !$collector->isNew())
    {
      $collector->setLastSeenAt(time());
      $collector->setSessionId(($boolean == true) ? session_id() : null);
      $collector->save();
    }

    return true;
  }

  protected function generateRandomKey($len = 20)
  {
    return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
  }

  /**
   * @return null|Collector
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

  public function clearAttributes()
  {
    parent::clearAttributes();

    $this->getAttributeHolder()->removeNamespace('collector');
    $this->getAttributeHolder()->removeNamespace('seller');
  }

  public function __call($m, $a)
  {
    $collector = $this->getCollector();

    if ($collector && method_exists($collector, $m))
    {
      return call_user_func_array(array($collector, $m), $a);
    }
    else
    {
      return parent::__call($m, $a);
    }
  }
}
