<?php

/**
 * @method  boolean  isOwnerOf($something)
 */
class cqUser extends IceSecurityUser
{
  /** @var Collector */
  private static $collector = null;

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

      setCookie('remember', null, 0, '/', str_replace('http://www', '', sfConfig::get('app_www_domain')));
      $_COOKIE['remember'] = null;

      self::$collector = null;
    }
    else if ($collector instanceof Collector && $boolean == true)
    {
      $this->addCredential(strtolower($collector->getUserType()));

      $this->setAttribute('id', $collector->getId(), 'collector');
      $this->setAttribute('profile_id', $collector->getProfile()->getId(), 'collector');
      $this->setAttribute('username', $collector->getUsername(), 'collector');
      $this->setAttribute('email', $collector->getEmail(), 'collector');
      $this->setAttribute('user_type', $collector->getUserType(), 'collector');

      if ($remember)
      {
        $cookie = serialize(array('username' => $collector->getUsername(), 'password' => $collector->getSha1Password()));
        setCookie('remember', $cookie, time()+60*60*24*14, '/', str_replace('http://www', '', sfConfig::get('app_www_domain')));
      }

      self::$collector = $collector;
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

  public function isAuthenticated()
  {
    $authenticated = parent::isAuthenticated();

    if (!$authenticated)
    {
      if (isset($_COOKIE['remember']) && $cookie = $_COOKIE['remember'])
      {
        $cookie = @unserialize($cookie);

        $c = new Criteria();
        $c->add(CollectorPeer::USERNAME, $cookie['username']);
        $collector = CollectorPeer::doSelectOne($c);

        // collector exists and password OK?
        if ($collector && $cookie['password'] == $collector->getSha1Password())
        {
          $authenticated = $this->Authenticate(true, $collector);
        }
        else
        {
          setCookie('remember', null, 0, '/');
          $_COOKIE['remember'] = null;
        }
      }
    }

    return $authenticated;
  }

  /**
   * @return null|Collector
   */
  public function getCollector()
  {
    if (!(self::$collector instanceof Collector))
    {
      if (self::$collector === null && ($this->getAttribute("id", null, "collector") !== null))
      {
        self::$collector = CollectorPeer::retrieveByPK($this->getAttribute("id", null, "collector"));
      }
      else
      {
        self::$collector = new Collector();
        self::$collector->setId(-1);
      }
    }
    else if (self::$collector->getId() == -1 && $this->getAttribute("id", null, "collector") !== null)
    {
      self::$collector = CollectorPeer::retrieveByPK($this->getAttribute("id", null, "collector"));
    }

    return self::$collector;
  }

  public function getLogoutUrl($next = null)
  {
    return '@logout?r='. $next;
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
