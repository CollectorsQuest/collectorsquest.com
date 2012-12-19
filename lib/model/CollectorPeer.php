<?php

require 'lib/model/om/BaseCollectorPeer.php';

class CollectorPeer extends BaseCollectorPeer
{
  const PROPERTY_CQNEXT_ACCESS_ALLOWED = 'CQNEXT_ACCESS_ALLOWED';
  const PROPERTY_CQNEXT_ACCESS_ALLOWED_DEFAULT_VALUE = 0;

  const PROPERTY_SELLER_SETTINGS_PAYPAL_ACCOUNT_ID = 'SELLER_SETTINGS_PAYPAL_ACCOUNT_ID';
  const PROPERTY_SELLER_SETTINGS_PAYPAL_ACCOUNT_STATUS = 'SELLER_SETTINGS_PAYPAL_ACCOUNT_STATUS';
  const PROPERTY_SELLER_SETTINGS_PAYPAL_BUSINESS_NAME = 'SELLER_SETTINGS_PAYPAL_BUSINESS_NAME';
  const PROPERTY_SELLER_SETTINGS_PAYPAL_EMAIL = 'SELLER_SETTINGS_PAYPAL_EMAIL';
  const PROPERTY_SELLER_SETTINGS_PAYPAL_FIRST_NAME = 'SELLER_SETTINGS_PAYPAL_FIRST_NAME';
  const PROPERTY_SELLER_SETTINGS_PAYPAL_LAST_NAME = 'SELLER_SETTINGS_PAYPAL_LAST_NAME';

  const PROPERTY_SELLER_SETTINGS_PHONE_CODE = 'SELLER_SETTINGS_PHONE_CODE';
  const PROPERTY_SELLER_SETTINGS_PHONE_NUMBER = 'SELLER_SETTINGS_PHONE_NUMBER';
  const PROPERTY_SELLER_SETTINGS_PHONE_EXTENSION = 'SELLER_SETTINGS_PHONE_EXTENSION';
  const PROPERTY_SELLER_SETTINGS_STORE_NAME = 'SELLER_SETTINGS_STORE_NAME';
  const PROPERTY_SELLER_SETTINGS_STORE_TITLE = 'SELLER_SETTINGS_STORE_TITLE';
  const PROPERTY_SELLER_SETTINGS_STORE_DESCRIPTION = 'SELLER_SETTINGS_STORE_DESCRIPTION';
  const PROPERTY_SELLER_SETTINGS_RETURN_POLICY = 'SELLER_SETTINGS_RETURN_POLICY';
  const PROPERTY_SELLER_SETTINGS_PAYMENT_ACCEPTED = 'SELLER_SETTINGS_PAYMENT_ACCEPTED';
  const PROPERTY_SELLER_SETTINGS_WELCOME = 'SELLER_SETTINGS_WELCOME';
  const PROPERTY_SELLER_SETTINGS_SHIPPING = 'SELLER_SETTINGS_SHIPPING';
  const PROPERTY_SELLER_SETTINGS_REFUNDS = 'SELLER_SETTINGS_REFUNDS';
  const PROPERTY_SELLER_SETTINGS_ADDITIONAL_POLICIES = 'SELLER_SETTINGS_ADDITIONAL_POLICIES';

  const PROPERTY_SELLER_SETTINGS_TAX_COUNTRY = 'SELLER_SETTINGS_TAX_COUNTRY';
  const PROPERTY_SELLER_SETTINGS_TAX_STATE = 'SELLER_SETTINGS_TAX_STATE';
  const PROPERTY_SELLER_SETTINGS_TAX_PERCENTAGE = 'SELLER_SETTINGS_TAX_PERCENTAGE';

  const PROPERTY_VISITOR_INFO_FIRST_VISIT_AT = 'VISITOR_INFO_FIRST_VISIT_AT';
  const PROPERTY_VISITOR_INFO_LAST_VISIT_AT = 'VISITOR_INFO_LAST_VISIT_AT';
  const PROPERTY_VISITOR_INFO_NUM_VISITS = 'VISITOR_INFO_NUM_VISITS';
  const PROPERTY_VISITOR_INFO_NUM_PAGE_VIEWS = 'VISITOR_INFO_NUM_PAGE_VIEWS';

  static public $visitor_info_props = array(
      self::PROPERTY_VISITOR_INFO_FIRST_VISIT_AT,
      self::PROPERTY_VISITOR_INFO_LAST_VISIT_AT,
      self::PROPERTY_VISITOR_INFO_NUM_VISITS,
      self::PROPERTY_VISITOR_INFO_NUM_PAGE_VIEWS,
  );

  const PROPERTY_PREFERENCES_SHOW_AGE = 'PREFERENCES_SHOW_AGE';
  const PROPERTY_PREFERENCES_SHOW_AGE_DEFAULT = false;
  const PROPERTY_PREFERENCES_MSG_ON = 'PREFERENCES_MSG_ON';
  const PROPERTY_PREFERENCES_MSG_ON_DEFAULT = true;
  const PROPERTY_PREFERENCES_INVITE_ONLY = 'PREFERENCES_INVITE_ONLY';
  const PROPERTY_PREFERENCES_INVITE_ONLY_DEFAULT = false;
  const PROPERTY_PREFERENCES_NEWSLETTER = 'PREFERENCES_NEWSLETTER';
  const PROPERTY_PREFERENCES_NEWSLETTER_DEFAULT = true;
  const PROPERTY_PREFERENCES_NEWSLETTER_OPT_OUT = 'PREFERENCES_NEWSLETTER_OPT_OUT';
  const PROPERTY_PREFERENCES_NEWSLETTER_OPT_OUT_DEFAULT = false;

  const PROPERTY_NOTIFICATIONS_COMMENT = 'NOTIFICATIONS_COMMENT';
  const PROPERTY_NOTIFICATIONS_COMMENT_DEFAULT = true;
  const PROPERTY_NOTIFICATIONS_COMMENT_OPT_OUT = 'NOTIFICATIONS_COMMENT_OPT_OUT';
  const PROPERTY_NOTIFICATIONS_COMMENT_OPT_OUT_DEFAULT = false;
  const PROPERTY_NOTIFICATIONS_MESSAGE = 'NOTIFICATIONS_MESSAGE';
  const PROPERTY_NOTIFICATIONS_MESSAGE_DEFAULT = true;
  const PROPERTY_NOTIFICATIONS_MESSAGE_OPT_OUT = 'NOTIFICATIONS_MESSAGE_OPT_OUT';
  const PROPERTY_NOTIFICATIONS_MESSAGE_OPT_OUT_DEFAULT = false;
  const PROPERTY_NOTIFICATIONS_BUDDY = 'NOTIFICATIONS_BUDDY';
  const PROPERTY_NOTIFICATIONS_BUDDY_DEFAULT = true;

  // Timeouts denote that the user is not allowed to perform a specific action
  // until the timeout datatime is reached
  const PROPERTY_TIMEOUT_COMMENTS_AT = 'TIMEOUT_COMMENTS_AT';
  const PROPERTY_TIMEOUT_PRIVATE_MESSAGES_AT = 'TIMEOUT_PRIVATE_MESSAGES_AT';

  const PROPERTY_TIMEOUT_IGNORE_FOR_USER = 'TIMEOUT_IGNORE_FOR_USER';

  const TYPE_COLLECTOR = 'Collector';
  const TYPE_SELLER = 'Seller';

  static public $default_avatar_ids = array(
    '159763', '2551491', '2805695', '12528549', '13709194',
    '13721607', '14193613', '17227104', '17744242', '18048813'
  );

  const TAGS_NAMESPACE_SELLER     = 'collector';
  const TAGS_KEY_I_COLLECT        = 'icollect';
  const TAGS_NAMESPACE_COLLECTOR  = 'seller';
  const TAGS_KEY_I_SELL           = 'isell';

  const PAYPAL_ACCOUNT_STATUS_VERIFIED = 'VERIFIED';

  const MULTIMEDIA_ROLE_STOREFRONT_HEADER_IMAGE = 'storefront_header_image';

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

      $time_limit_target = strtotime($time_limit, (integer) $time_of_hash);

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

    $query = '
      SELECT DISTINCT zip2.COLLECTOR_ID AS id,
             3963 * ACOS( COS( RADIANS(zip1.latitude) ) * COS( RADIANS( zip2.latitude ) ) *
             COS( RADIANS( zip2.longitude ) - RADIANS(zip1.longitude) ) + SIN( RADIANS(zip1.latitude) ) *
             SIN( RADIANS( zip2.latitude ) ) )  AS distance_in_miles
        FROM %s zip1, %s zip2
       WHERE zip1.zip_postal = %d
      HAVING distance_in_miles <= %d
       ORDER BY distance_in_miles ASC;
    ';

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
    $collector_identifier->setProvider($collector_identifier->getProviderFromIdentifier());
    $collector_identifier->save();

    return $collector;
  }

  public static function createFromArray($data = array())
  {
    // We need to make sure we have a display name
    $display_name = !empty($data['display_name']) ? $data['display_name'] : $data['username'];

    $collector = new Collector();
    $collector->setUsername($data['username']);
    $collector->setPassword($data['password']);
    $collector->setDisplayName($display_name);
    $collector->setEmail($data['email']);
    $collector->setUserType(isset($data['seller']) && !!$data['seller']
      ? CollectorPeer::TYPE_SELLER
      : CollectorPeer::TYPE_COLLECTOR
    );
    $collector->setPreferencesNewsletter(isset($data['newsletter'])
      ? (boolean) $data['newsletter']
      : CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER_DEFAULT
    );
    /**
     * Temporary disable before tests are written
     *
       if (!empty($data['facebook_id']))
       {
         $collector->setFacebookId($data['facebook_id']);
       }
    */

    // All of the profile data is optional, thus make sure to check it is provided
    $collector_profile = new CollectorProfile();
    $collector_profile->setCollector($collector);
    $collector_profile->setProfileCompleted(25);

    // set profile country code if present
    if (isset($data['country_iso3166']) && false !== $data['country_iso3166'])
    {
      $collector_profile->setCountryIso3166($data['country_iso3166']);
    }

    // default to casual collector
    $collector_profile->setCollectorType(isset($data['collector_type'])
      ? $data['collector_type']
      : 'casual');

    $collector->save();
    $collector_profile->save();

    if (!empty($data['email']))
    {
      $collectorEmail = new CollectorEmail();
      $collectorEmail->setCollector($collector);
      $collectorEmail->setEmail($collector->getEmail());
      $collectorEmail->setSalt($collector->generateSalt());
      $collectorEmail->setHash($collector->getAutoLoginHash());
      $collectorEmail->setIsVerified(false);
      $collectorEmail->save();
    }

    return $collector;
  }

  public static function getCity2Tags($max = 50)
  {
    $tags = array();

    $con = Propel::getConnection();
    $query = '
      SELECT DISTINCT CONCAT(collector_geocache.city, \', \', UPPER(collector_geocache.state)) AS tag,
             collector_geocache.city AS city,
             collector_geocache.state AS state,
             GROUP_CONCAT(collector_geocache.zip_postal) AS zip,
             COUNT(DISTINCT collector_geocache.collector_id) AS count
        FROM collector_geocache
       WHERE collector_geocache.country_iso3166 = \'US\' AND city IS NOT NULL
       GROUP BY tag
       ORDER BY count DESC, tag DESC
       LIMIT 0, {$max}
    ';

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
    $query = sprintf('
      SELECT %s AS tag, COUNT(*) AS count
        FROM %s
        JOIN %s
         ON %s = %s
        GROUP BY %s
        ORDER BY count DESC
        LIMIT 0, %d
      ',
      /*select*/    iceModelGeoCountryPeer::NAME,
      /*from*/      CollectorProfilePeer::TABLE_NAME,
      /*join*/      iceModelGeoCountryPeer::TABLE_NAME,
      /*on*/        CollectorProfilePeer::COUNTRY_ISO3166, iceModelGeoCountryPeer::ISO3166,
      /*group by*/  iceModelGeoCountryPeer::NAME,
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

  /**
   * Listener for user.change_authentication
   *
   * Used to set COOKIE_UUID from the request cookie;
   * Because the cookie can change at some points (ie, using the site form a different PC),
   * it's preferred to keep the collector's COOKIE_UUID updating based on that cookie.
   *
   * @param     sfEvent $event
   */
  public static function listenToChangeAuthenticationEvent(sfEvent $event)
  {
    $params = $event->getParameters();
    /** @var $cq_user cqFrontendUser */
    $cq_user = $event->getSubject();

    // if the user is beign authenticated
    if (true == $params['authenticated'])
    {
      // and we can successfully get the related collector
      if (( $collector = $cq_user->getCollector($strict = true) ))
      {
        if (( $uuid = $cq_user->getCookieUuid() ))
        {
          // remove the UUID from the last user that had it
          CollectorQuery::create()
            ->filterByCookieUuid($uuid)
            ->update(array('CookieUuid' => null));

          // and set it to the current user
          $collector->setCookieUuid($uuid);
        }

        if (($visitor_info = $cq_user->getVisitorInfoArray() ))
        {
          $collector->mergeVisitorInfoArray($visitor_info);
        }

        $collector->save();
      }
    }
  }

}
