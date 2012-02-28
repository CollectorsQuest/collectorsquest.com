<?php

require 'lib/model/om/BaseCollectorProfile.php';

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

  /**
   * @param string|null $now Current date in a compatible format http://www.php.net/manual/en/datetime.formats.date.php
   * @return integer
   */
  public function getAge($now = null)
  {
    $birthdate_dt = $this->getBirthday(null);
    return $birthdate_dt->diff( new DateTime($now) )->y;
  }

  public function getCountry()
  {
    if (( $country = $this->geticeModelGeoCountry() ))
    {
      return $country->getName();
    }

    return null;
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
    return parent::setZipPostal(strtoupper($v));
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
      case 'casual':
      default:
        return '#EFBF81';
        break;
      case 'occasional':
        return '#4B69B6';
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
        // if USA, United Kingdom, Canada, Australia
        if (in_array($this->getCountryIso3166(), array('US', 'GB', 'CA', 'AU')))
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

  public function getAboutCollections()
  {
    return $this->getProperty('about.collections');
  }

  public function setAboutCollections($v)
  {
    return $this->setProperty('about.collections', $v);
  }

  public function getAboutAnnuallySpend()
  {
    return $this->getProperty('about.annually_spend');
  }

  public function setAboutAnnuallySpend($v)
  {
    return $this->setProperty('about.annually_spend', $v);
  }

  public function getAboutWhatYouCollect()
  {
    return $this->getProperty('about.what_you_collect');
  }

  public function setAboutWhatYouCollect($v)
  {
    return $this->setProperty('about.what_you_collect', trim(str_replace(array(',', ',  '), ', ', $v), ', '));
  }

  public function getAboutWhatYouSell()
  {
    return $this->getProperty('about.what_you_sell');
  }

  public function setAboutWhatYouSell($v)
  {
    return $this->setProperty('about.what_you_sell', trim(str_replace(array(',', ',  '), ', ', $v), ', '));
  }

  public function getAboutPurchasesPerYear()
  {
    return $this->getProperty('about.purchases_per_year');
  }

  public function setAboutPurchasesPerYear($v)
  {
    return $this->setProperty('about.purchases_per_year', $v);
  }

  public function getAboutMostExpensiveItem()
  {
    return $this->getProperty('about.most_expensive_item');
  }

  public function setAboutMostExpensiveItem($v)
  {
    return $this->setProperty('about.most_expensive_item', $v);
  }

  public function getAboutCompany()
  {
    return $this->getProperty('about.company');
  }

  public function setAboutCompany($v)
  {
    return $this->setProperty('about.company', $v);
  }

  public function getAboutNewItemEvery()
  {
    return $this->getProperty('about.new_item_every');
  }

  public function setAboutNewItemEvery($v)
  {
    return $this->setProperty('about.new_item_every', $v);
  }

  public function getAboutMe()
  {
    return $this->getProperty('about.me');
  }

  public function setAboutMe($v)
  {
    return $this->setProperty('about.me', $v);
  }

  public function getAboutInterests()
  {
    return $this->getProperty('about.interests');
  }

  public function setAboutInterests($v)
  {
    return $this->setProperty('about.interests', $v);
  }
}
