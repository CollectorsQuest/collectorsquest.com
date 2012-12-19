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
   * @param     string|null $now Current date in a compatible format:
   *                         http://www.php.net/manual/en/datetime.formats.date.php
   * @return    integer
   */
  public function getAge($now = null)
  {
    $birthdate_dt = $this->getBirthday(null);
    return $birthdate_dt->diff( new DateTime($now) )->y;
  }

  /**
   * @return    string|null
   */
  public function getCountryName()
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

    return parent::setWebsite($v);
  }

  public function getWebsiteUrl()
  {
    if ($website = $this->getWebsite())
    {
      return IceWebBrowser::formatUrl($website);
    }

    return null;
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
    return $this->getCollector()->getICollect();
  }

  public function setAboutWhatYouCollect($v)
  {
    $this->getCollector()->setICollectTags($v);
    return $this;
  }

  public function getAboutWhatYouSell()
  {
    return $this->getCollector()->getISell();
  }

  public function setAboutWhatYouSell($v)
  {
    $this->getCollector()->setISellTags($v);
    return $this;
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
    return $this->setProperty('about.company', strip_tags($v));
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
    return $this->setProperty('about.me', strip_tags($v));
  }

  public function getAboutInterests()
  {
    return $this->getProperty('about.interests');
  }

  public function setAboutInterests($v)
  {
    return $this->setProperty('about.interests', strip_tags($v));
  }

  /**
   * Exports the object as an array.
   *
   * Will export the aditional fields handled by the ExtraProperties Propel
   * behavior as well
   *
   * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
   *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
   *                    Defaults to BasePeer::TYPE_PHPNAME.
   * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
   * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
   * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
   *
   * @return    array an associative array containing the field names (as keys) and field values
   */
  public function toArray(
    $keyType = BasePeer::TYPE_PHPNAME,
    $includeLazyLoadColumns = true,
    $alreadyDumpedObjects = array(),
    $includeForeignObjects = false
  ) {
    $result = parent::toArray(
      $keyType,
      $includeLazyLoadColumns,
      $alreadyDumpedObjects,
      $includeForeignObjects);

    if (!is_array($result))
    {
      // recursion
      return $result;
    }

    $extra_fields = array();
    if (isset(CollectorProfilePeer::$extraFieldNames[$keyType]))
    {
      $keys = CollectorProfilePeer::$extraFieldNames[$keyType];

      $extra_fields = array(
          $keys[0] => $this->getAboutMe(),
          $keys[1] => $this->getAboutCompany(),
          $keys[2] => $this->getAboutCollections(),
          $keys[3] => $this->getAboutWhatYouCollect(),
          $keys[4] => $this->getAboutWhatYouSell(),
          $keys[5] => $this->getAboutMostExpensiveItem(),
          $keys[6] => $this->getAboutAnnuallySpend(),
          $keys[7] => $this->getAboutPurchasesPerYear(),
          $keys[8] => $this->getAboutNewItemEvery(),
          $keys[9] => $this->getAboutInterests(),
      );
    }

    return array_merge($result, $extra_fields);
  }

  /**
   * Populates the object using an array.
   *
   * Will set the aditional fields handled by the ExtraProperties Propel
   * behavior as well
   *
   * @param      array  $arr     An array to populate the object from.
   * @param      string $keyType The type of keys the array uses.
   * @return     void
   */
  public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
  {
    parent::fromArray($arr, $keyType);

    if (isset(CollectorProfilePeer::$extraFieldNames[$keyType]))
    {
      $keys = CollectorProfilePeer::$extraFieldNames[$keyType];

      if (array_key_exists($keys[0], $arr)) $this->setAboutMe($arr[$keys[0]]);
      if (array_key_exists($keys[1], $arr)) $this->setAboutCompany($arr[$keys[1]]);
      if (array_key_exists($keys[2], $arr)) $this->setAboutCollections($arr[$keys[2]]);
      if (array_key_exists($keys[3], $arr)) $this->setAboutWhatYouCollect($arr[$keys[3]]);
      if (array_key_exists($keys[4], $arr)) $this->setAboutWhatYouSell($arr[$keys[4]]);
      if (array_key_exists($keys[5], $arr)) $this->setAboutMostExpensiveItem($arr[$keys[5]]);
      if (array_key_exists($keys[6], $arr)) $this->setAboutAnnuallySpend($arr[$keys[6]]);
      if (array_key_exists($keys[7], $arr)) $this->setAboutPurchasesPerYear($arr[$keys[7]]);
      if (array_key_exists($keys[8], $arr)) $this->setAboutNewItemEvery($arr[$keys[8]]);
      if (array_key_exists($keys[9], $arr)) $this->setAboutInterests($arr[$keys[9]]);
    }
  }

  public function getProfileCompleted()
  {
    return $this->getProperty('completed', 25);
  }

  public function setProfileCompleted($value)
  {
    return $this->setProperty('completed', $value);
  }

  public function updateProfileProgress()
  {
    $percentage = 25; // 25% default for registered
    $collector = $this->getCollector();

    if ($collector->countCollectorCollections() > 0)
    {
      $percentage += 25;

      if ($collector->countCollectibles() > 0)
      {
        $percentage += 25;

        if ($this->getAboutWhatYouCollect())
        {
          $percentage += 25;
        }
      }
    }

    $this->setProfileCompleted($percentage);
    $this->save();
  }

}
