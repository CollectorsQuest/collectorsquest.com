<?php

/**
 * Subclass for representing a row from the 'collector_profile' table.
 *
 * @package lib.model
 */
class CollectorProfile extends BaseCollectorProfile
{
  private $location = array();
  private $geocache  = array();

  public function getDisplayName()
  {
    $display_name = $this->getCollector()->getDisplayName();

    return $display_name;
  }

  public function setBirthday($v)
  {
    if (is_array($v))
    {
      $v = sprintf(
        '%s-%s-%s',
        isset($v['year']) ? $v['year'] : '0000',
        isset($v['month']) ? $v['month'] : '01',
        isset($v['day']) ? $v['day'] : '01'
      );
    }

    parent::setBirthday($v);
  }

  public function getAge()
  {
    $c = new Criteria();
    $c->addAsColumn("age", "(YEAR(CURRENT_DATE())-YEAR(birthday)-(RIGHT(CURRENT_DATE(),5)<RIGHT(birthday,5)))");
    $c->addSelectColumn(CollectorProfilePeer::ID);
    $c->add(CollectorProfilePeer::ID, $this->getId());
    $c->setLimit(1);

    $stmt = CollectorProfilePeer::doSelectStmt($c);

    return $stmt->fetchColumn(1);
  }

  public function getAddress()
  {
    $this->_populateGeoCache();

    if ($this->getCountryIso3166() == "US" && $this->getCity() && $this->getState())
    {
      $city  = $this->getCity();
      $city  = (!$city) ? $this->getZipPostal() : $city;
      $state = $this->getState();

      return $city .", ". $state;
    }
    else
    {
      return $this->getCountry();
    }
  }

  public function setGender($v)
  {
    if (in_array(strtolower($v), array('female', 'girl', 'f')))
    {
      $v = 'f';
    }
    else if (in_array(strtolower($v), array('male', 'boy', 'm')))
    {
      $v = 'm';
    }
    else
    {
      $v = null;
    }

    parent::setGender($v);
  }

  public function getCity()
  {
    $this->_populateGeoCache();

    return @$this->geocache['city'];
  }

  public function getState()
  {
    $this->_populateGeoCache();

    return @$this->geocache['state'];
  }

  public function setZip($v)
  {
    return parent::setZip(strtoupper($v));
  }

  public function getTimeZone()
  {
    $this->_populateGeoCache();

    return @$this->geocache['timezone'];
  }

  public function getCollectorTypeColor()
  {
    $type = $this->getCollectorType();

    switch ($type)
    {
      case 'occasional':
        return '#4B69B6';
        break;
      case 'casual':
        return '#EFBF81';
        break;
      case 'obsessive':
        return '#8BC3D3';
        break;
      case 'serious':
        return '#BADC70';
        break;
      case 'expert':
        return '#E96060';
        break;
    }
  }

  public function getCollecting()
  {
  	return trim(str_replace(array(',', ',  '), ', ', parent::getCollecting()), ', ');
  }

  public function setWebsite($v)
  {
    $v = IceWebBrowser::formatUrl($v);
    $v = preg_replace(array('/^http:\/\//i', '/^https:\/\//i'), '', $v);
    $v = preg_replace('/^www\./', '', $v);

    parent::setWebsite($v);
  }

  public function getWebsiteUrl()
  {
    if ($website = $this->getWebsite())
    {
      return IceWebBrowser::formatUrl($website);
    }

    return null;
  }

  public function setPreferences($v)
  {
    if (empty($v))
    {
      $v = array(
        'show_age'    => false,
        'msg_on'      => true,
        'invite_only' => false
      );
    }

    parent::setPreferences(serialize((array) $v));
  }

  public function getPreferences()
  {
    $v = @unserialize(parent::getPreferences());

    return (array) $v;
  }

  public function setNotifications($v)
  {
    if (empty($v))
    {
      $v = array(
        'comment' => true,
        'buddy'   => true,
        'message' => true
      );
    }

    parent::setNotifications(serialize((array) $v));
  }

  public function getNotifications()
  {
    $v = @unserialize(parent::getNotifications());

    return (array) $v;
  }

  public function getGeoCache()
  {
    if (empty($this->geocache))
    {
      $this->_populateGeoCache();
    }

    return $this->geocache;
  }

  public function _populateGeoCache()
  {
    if (!empty($this->geocache))
    {
      return;
    }

    $c = new Criteria();
    $c->add(CollectorGeocachePeer::COLLECTOR_ID, $this->getCollectorId());

    if ($collector_geocache = CollectorGeocachePeer::doSelectOne($c))
    {
      $this->geocache = array(
        'country'         => $collector_geocache->getCountry(),
        'country_iso3166' => $collector_geocache->getCountryIso3166(),
        'state'           => $collector_geocache->getState(),
        'county'          => $collector_geocache->getCounty(),
        'city'            => $collector_geocache->getCity(),
        'zip_postal'      => $collector_geocache->getZipPostal(),
        'address'         => $collector_geocache->getAddress(),
        'latitude'        => $collector_geocache->getLatitude(),
        'longitude'       => $collector_geocache->getLongitude()
      );
    }
    else
    {
      if (empty($this->location))
      {
        if (in_array($this->getCountry(), array('US', 'USA', 'United States', 'UK', 'United Kingdom', 'CA', 'Canada', 'Australia')))
        {
          $this->location = $this->getZipPostal() .', '. $this->getCountry();
        }
        else
        {
          $this->location = $this->getCountry();
        }
      }

      $this->geocache = Geocoder::getGeocode($this->location);
      $this->geocache = array_shift($this->geocache);

      if ($this->geocache)
      {
        $collector_geocache = new CollectorGeocache();
        $collector_geocache->setCollector($this->getCollector());
        $collector_geocache->setCountry($this->geocache['country']);
        $collector_geocache->setCountryIso3166($this->geocache['country_iso3166']);
        $collector_geocache->setState($this->geocache['state']);
        $collector_geocache->setCounty($this->geocache['county']);
        $collector_geocache->setCity($this->geocache['city']);
        $collector_geocache->setZipPostal($this->geocache['zip_postal']);
        $collector_geocache->setAddress($this->geocache['address']);
        $collector_geocache->setLatitude($this->geocache['latitude']);
        $collector_geocache->setLongitude($this->geocache['longitude']);
        $collector_geocache->setTimezone($this->geocache['timezone']);

        $collector_geocache->save();
      }
    }
  }
}
