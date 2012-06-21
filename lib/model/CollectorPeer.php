<?php

require 'lib/model/om/BaseCollectorPeer.php';

class CollectorPeer extends BaseCollectorPeer
{
  const PROPERTY_CQNEXT_ACCESS_ALLOWED = 'CQNEXT_ACCESS_ALLOWED';
  const PROPERTY_CQNEXT_ACCESS_ALLOWED_DEFAULT_VALUE = 0;

  const PROPERTY_SELLER_SETTINGS_PAYPAL_EMAIL = 'SELLER_SETTINGS_PAYPAL_EMAIL';
  const PROPERTY_SELLER_SETTINGS_PHONE_CODE = 'SELLER_SETTINGS_PHONE_CODE';
  const PROPERTY_SELLER_SETTINGS_PHONE_NUMBER = 'SELLER_SETTINGS_PHONE_NUMBER';
  const PROPERTY_SELLER_SETTINGS_PHONE_EXTENSION = 'SELLER_SETTINGS_PHONE_EXTENSION';
  const PROPERTY_SELLER_SETTINGS_STORE_DESCRIPTION = 'SELLER_SETTINGS_STORE_DESCRIPTION';
  const PROPERTY_SELLER_SETTINGS_RETURN_POLICY = 'SELLER_SETTINGS_RETURN_POLICY';
  const PROPERTY_SELLER_SETTINGS_PAYMENT_ACCEPTED = 'SELLER_SETTINGS_PAYMENT_ACCEPTED';
  const PROPERTY_SELLER_SETTINGS_WELCOME = 'SELLER_SETTINGS_WELCOME';
  const PROPERTY_SELLER_SETTINGS_SHIPPING = 'SELLER_SETTINGS_SHIPPING';
  const PROPERTY_SELLER_SETTINGS_REFUNDS = 'SELLER_SETTINGS_REFUNDS';
  const PROPERTY_SELLER_SETTINGS_ADDITIONAL_POLICIES = 'SELLER_SETTINGS_ADDITIONAL_POLICIES';

  const TYPE_COLLECTOR = 'Collector';
  const TYPE_SELLER = 'Seller';

  static public $avatars = array(
    '159763', '2551491', '2805695', '12528549', '13709194',
    '13721607', '14193613', '17227104', '17744242', '18048813'
  );

  /**
   * @param     string $username
   * @param     PropelPDO $con
   * @return    Collector|null
   */
  public static function retrieveByUsername($username, PropelPDO $con = null)
  {
    $c = new Criteria();
    $c->add(self::USERNAME, $username);

    return self::doSelectOne($c, $con);
  }

  /**
   * @param     string $slug
   * @param     PropelPDO $con
   * @return    Collector|null
   */
  public static function retrieveBySlug($slug, PropelPDO $con = null)
  {
    $c = new Criteria();
    $c->add(self::SLUG, $slug);

    return self::doSelectOne($c, $con);
  }

  /**
   * @param     string $hash
   * @param     PropelPDO $con
   * @return    Collector|null
   */
  public static function retrieveByHash($hash, PropelPDO $con = null)
  {
    if (!empty($hash))
    {
      // Split the Hash parts
      @list($version, $id, $hmac, $time) = explode(';', $hash);

      // Try to get the Collector object
      if (( $collector = self::retrieveByPk($id, $con) ))
      {
        // Finally check if the $hash is valid
        return $collector->getAutoLoginHash($version, $time) === $hash ? $collector : null;
      }
    }

    return null;
  }

  /**
   * @param     string $identifier
   * @param     PropelPDO $con
   * @return    Collector|null
   */
  public static function retrieveByIdentifier($identifier, PropelPDO $con = null)
  {
    $c = new Criteria();
    $c->addJoin(CollectorPeer::ID, CollectorIdentifierPeer::COLLECTOR_ID);
    $c->add(CollectorIdentifierPeer::IDENTIFIER, $identifier);

    return self::doSelectOne($c, $con);
  }

  /**
   * Retrieve a Collector record only if within the time limit from the hash generation
   *
   * @param     string $hash
   * @param     string $time_limit strtotime compatible time distance from hash generation time
   * @param     integer $time The current time
   *
   * @return    Collector|null
   */
  public static function retrieveByHashTimeLimited($hash, $time_limit, $time = null)
  {
    if (!empty($hash))
    {
      if (null === $time)
      {
        $time = time();
      }

      // Split the Hash parts
      @list($version, $id, $hmac, $time_of_hash) = explode(';', $hash);

      $time_limit_target = strtotime($time_limit, (int)$time_of_hash);

      if ($time_limit_target < $time)
      {
        return null;
      }

      // Try to get the Collector object
      if (( $collector = self::retrieveByPk($id) ))
      {
        // Finally check if the $hash is valid
        return $collector->getAutoLoginHash($version, $time_of_hash) === $hash ? $collector : null;
      }
    }

    return null;
  }

  /**
   * Get all the Collectors around a certain zip code
   *
   * @param  string   $zip
   * @param  int      $miles
   * @param  boolean  $only_pks
   *
   * @todo  Chech the URL bellow and implement that as a permanent solution
   * @see http://www.phpro.org/tutorials/Geo-Targetting-With-PHP-And-MySQL.html
   *
   * @return array
   */
  public static function retrieveByDistance($zip, $miles, $only_pks = false)
  {
    $con = Propel::getConnection();

    $query = "
      SELECT DISTINCT zip2.COLLECTOR_ID AS id,
             3963 * ACOS( COS( RADIANS(zip1.latitude) ) * COS( RADIANS( zip2.latitude ) ) * COS( RADIANS( zip2.longitude ) - RADIANS(zip1.longitude) ) + SIN( RADIANS(zip1.latitude) ) * SIN( RADIANS( zip2.latitude ) ) )  AS distance_in_miles
        FROM %s zip1, %s zip2
       WHERE zip1.zip_postal = %d
      HAVING distance_in_miles <= %d
       ORDER BY distance_in_miles ASC;
    ";

    $query = sprintf(
      $query, CollectorGeocachePeer::TABLE_NAME, CollectorGeocachePeer::TABLE_NAME, $zip, $miles
    );

    $stmt = $con->prepare($query);
    $stmt->execute();

    $pks = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $pks[] = $row['id'];
    }

    return ($only_pks === true) ? $pks : CollectorPeer::retrieveByPKs($pks);
  }

  public static function getObjectForRoute($parameters)
  {
    $collector = null;

    if (isset($parameters['collector_id']))
    {
      $collector = self::retrieveByPk($parameters['collector_id']);
    }
    else if (isset($parameters['id']))
    {
      $collector = self::retrieveByPk($parameters['id']);
    }
    else if (isset($parameters['collector_slug']))
    {
      $parameters['slug'] = str_replace(array('.html', '.htm'), '', $parameters['collector_slug']);
      $collector = self::retrieveBySlug($parameters['collector_slug']);
    }
    else if (isset($parameters['slug']))
    {
      $parameters['slug'] = str_replace(array('.html', '.htm'), '', $parameters['slug']);
      $collector = self::retrieveBySlug($parameters['slug']);
    }

    return $collector;
  }

  public static function createFromRPXProfile($profile)
  {
    $email = !empty($profile['verifiedEmail']) ? $profile['verifiedEmail'] : $profile['email'];

    if (empty($email) || !$collector = CollectorQuery::create()->findOneByEmail($email))
    {
      // Build the data array
      $data = array(
        'username' => uniqid('rpx'),
        'password' => IceStatic::getUniquePassword(),
        'display_name' => !empty($profile['displayName']) ? $profile['displayName'] : $profile['preferredUsername'],
        'email' => $email,
        'birthday' => isset($profile['birthdate']) ? $profile['birthdate'] : null,
        'gender' => isset($profile['gender']) ? $profile['gender'] : null,
        'website' => isset($profile['url']) ? $profile['url'] : null
      );

      $collector = self::createFromArray($data);
    }

    /** @var $q CollectorIdentifierQuery */
    $q = CollectorIdentifierQuery::create()
       ->filterByIdentifier($profile['identifier']);

    $collector_identifier = $q->findOneOrCreate();
    $collector_identifier->setCollector($collector);
    $collector_identifier->save();

    return $collector;
  }

  public static function createFromArray($data = array())
  {
    // We need to make sure we have a display name
    $display_name = !empty($data['display_name']) ?
      $data['display_name'] :
      $data['username'];

    $collector = new Collector();
    $collector->setUsername($data['username']);
    $collector->setPassword($data['password']);
    $collector->setDisplayName($display_name);
    $collector->setEmail($data['email']);
    $collector->setUserType(isset($data['seller']) && !!$data['seller'] ? 'Seller' : 'Collector');

    /* Temporary disable before tests are written * /
    if (!empty($data['facebook_id']))
    {
      $collector->setFacebookId($data['facebook_id']);
    }
    /* */

    // All of the profile data is optional, thus make sure to check it is provided
    $collector_profile = new CollectorProfile();
    $collector_profile->setCollector($collector);
    $collector_profile->setProfileCompleted(25);

    $collector_profile->setPreferences(array(
      'show_age'    => false,
      'msg_on'      => true,
      'invite_only' => false
    ));

    $collector_profile->setNotifications(array(
      'comment' => true,
      'buddy'   => true,
      'message' => true
    ));


    // set profile country code if present
    if (isset($data['country_iso3166']) && false !== $data['country_iso3166'])
    {
      $collector_profile->setCountryIso3166($data['country_iso3166']);
    }

    // default to casual collector
    $collector_profile->setCollectorType(isset($data['collector_type'])
      ? $data['collector_type']
      : 'casual');

    try
    {
      $collector_profile->save();
      $collector->save();

      if (!empty($data['email']))
      {
        $collectorEmail = new CollectorEmail();
        $collectorEmail->setCollector($collector);
        $collectorEmail->setEmail($collector->getEmail());
        $collectorEmail->setSalt($collector->generateSalt());
        $collectorEmail->setHash($collector->getAutoLoginHash());
        $collectorEmail->setIsVerified(true);
        $collectorEmail->save();
      }
    }
    catch (PropelException $e)
    {
      return null;
    }

    return $collector;
  }

  public static function getCity2Tags($max = 50)
  {
    $tags = array();

    $con = Propel::getConnection();
    $query = "
      SELECT DISTINCT CONCAT(collector_geocache.city, ', ', UPPER(collector_geocache.state)) AS tag,
             collector_geocache.city AS city,
             collector_geocache.state AS state,
             GROUP_CONCAT(collector_geocache.zip_postal) AS zip,
             COUNT(DISTINCT collector_geocache.collector_id) AS count
        FROM collector_geocache
       WHERE collector_geocache.country_iso3166 = 'US' AND city IS NOT NULL
       GROUP BY tag
       ORDER BY count DESC, tag DESC
       LIMIT 0, {$max}
    ";

    $stmt = $con->prepare($query);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $zip = $row['zip'];
      $zip = array_unique(explode(',', $zip));
      $zip = implode('-', $zip);

      $tags[$row['tag']] = array(
        'count' => $row['count'],
        'zip'   => $zip
      );
    }

    ksort($tags, SORT_STRING);

    $tags = IceTaggableToolkit::weight_tags($tags, 6);

    return $tags;
  }

  public static function getCountry2Tags($max = 50)
  {
    $con = Propel::getConnection();
    $query = sprintf("
      SELECT %s AS tag, COUNT(*) AS count
        FROM %s
        JOIN %s
         ON %s = %s
       GROUP BY %s
       ORDER BY count DESC
       LIMIT 0, %d
    ",
/*select*/    GeoCountryPeer::NAME,
/*from*/      CollectorProfilePeer::TABLE_NAME,
/*join*/      GeoCountryPeer::TABLE_NAME,
/*on*/        CollectorProfilePeer::COUNTRY_ISO3166, GeoCountryPeer::ISO3166,
/*group by*/  GeoCountryPeer::NAME,
/*limit*/      $max
    );

    $stmt = $con->prepare($query);
    $stmt->execute();

    $tags = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      if (empty($row['tag']))
        continue;
      $tags[$row['tag']] = $row['count'];
    }

    $tags = IceTaggableToolkit::weight_tags($tags, 6);

    return $tags;
  }

  public static function getRelatedCollectors($object, $limit = 0, Criteria $criteria = null)
  {
    $pks = array();

    /** @var $collections CollectorCollection[] */
    $collections = CollectorCollectionPeer::getRelatedCollections($object, $limit, $criteria);
    foreach ($collections as $collection)
    {
      $pks = $collection->getCollectorId();
    }

    return CollectorPeer::retrieveByPKs($pks);
  }

  /* added by Prakash Panchal 13-APR-2011
   * updateCollectorAsSeller function.
   * return object
   */
  public static function updateCollectorAsSeller($amSellerInfo = array())
  {
    $omSeller = CollectorPeer::retrieveByPK($amSellerInfo['id']);

    $snTotalItemAllowed = ($amSellerInfo['items_allowed'] < 0) ? $amSellerInfo['items_allowed'] : (int)$omSeller->getItemsAllowed() + $amSellerInfo['items_allowed'];
    $omSeller->setUserType($amSellerInfo['user_type']);
    $omSeller->setItemsAllowed($snTotalItemAllowed);

    try
    {
      $omSeller->save();
    }
    catch (PropelException $e)
    {
      return false;
    }
    return $omSeller;
  }

  /**
   * @deprecated
   *
   * @param  integer $snSellerId
   * @return boolean|\Collector
   */
  public static function deductAllowedItems($snSellerId)
  {
    $omSeller = CollectorPeer::retrieveByPK($snSellerId);
    $omSeller->setItemsAllowed($omSeller->getItemsAllowed() - 1);

    try
    {
      $omSeller->save();
    }
    catch (PropelException $e)
    {
      return false;
    }
    return $omSeller;
  }

  public static function retrieveForSelect($q, $limit = 0)
  {
    $criteria = new Criteria();
    $criteria->clearSelectColumns();
    $criteria->addSelectColumn(self::ID);
    $criteria->addSelectColumn(self::DISPLAY_NAME);
    $criteria->setLimit($limit);

    $criteria->add(self::DISPLAY_NAME, '%' . mysql_real_escape_string($q) . '%', Criteria::LIKE);

    return self::doSelectStmt($criteria)->fetchAll(PDO::FETCH_KEY_PAIR);
  }

}
