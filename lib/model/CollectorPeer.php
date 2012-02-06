<?php

class CollectorPeer extends BaseCollectorPeer
{
  public static function retrieveBySlug($slug)
  {
    $c = new Criteria();
    $c->add(self::SLUG, $slug);

    return self::doSelectOne($c);
  }

  public static function retrieveByHash($hash)
  {
    if (!empty($hash))
    {
      // Split the Hash parts
      @list($version, $id, $hmac, $time) = explode(';', $hash);

      // Try to get the Collector object
      if ($collector = self::retrieveByPk($id))
      {
        // Finally check if the $hash is valid
        return $collector->getAutoLoginHash($version, $time) === $hash ? $collector : null;
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
      $parameters['slug'] = str_replace(array('.html', '.htm'), '', $parameters['slug']);
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

    if (!$collector_identifier = CollectorIdentifierQuery::create()->findOneByIdentifier($profile['identifier']))
    {
      $collector_identifier = new CollectorIdentifier();
      $collector_identifier->setCollector($collector);
      $collector_identifier->setIdentifier($profile['identifier']);
      $collector_identifier->save();
    }
    else
    {
      $collector_identifier->setCollector($collector);
      $collector_identifier->save();
    }

    return $collector;
  }

  public static function createFromArray($data = array())
  {
    $collector = new Collector();
    $collector->setUsername($data['username']);
    $collector->setPassword($data['password']);
    $collector->setDisplayName($data['display_name']);
    $collector->setEmail($data['email']);

    if (!empty($data['facebook_id']))
    {
      $collector->setFacebookId($data['facebook_id']);
    }

    // All of the profile data is optional, thus make sure to check it is provided
    $collector_profile = new CollectorProfile();
    $collector_profile->setCollector($collector);

    if (!empty($data['birthday']) && is_string($data['birthday']))
    {
      $collector_profile->setBirthday($data['birthday']);
    }
    if (!empty($data['gender']) && is_string($data['gender']))
    {
      $collector_profile->setGender($data['gender']);
    }
    if (!empty($data['zip_postal']))
    {
      $collector_profile->setZipPostal($data['zip_postal']);
    }
    if (!empty($data['country']))
    {
      $collector_profile->setCountry($data['country']);
    }
    if (!empty($data['website']) && is_string($data['website']))
    {
      $collector_profile->setWebsite($data['website']);
    }

    $collector_profile->setPreferences(array(
      'show_age' => false, 'msg_on' => true, 'invite_only' => false
    ));

    $collector_profile->setNotifications(array(
      'comment' => true, 'buddy' => true, 'message' => true
    ));

    try
    {
      $collector_profile->save();
      $collector->save();
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
       WHERE collector_geocache.country = 'USA' AND city IS NOT NULL
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
        'zip' => $zip
      );
    }

    ksort($tags, SORT_STRING);

    $tags = IceTaggableToolkit::weight_tags($tags, 6);

    return $tags;
  }

  public static function getCountry2Tags($max = 50)
  {
    $con = Propel::getConnection();
    $query = "
      SELECT %s AS tag, COUNT(%s) AS count
        FROM %s
       GROUP BY %s
       ORDER BY tag, count DESC
       LIMIT 0, %d
    ";

    $query = sprintf(
      $query, CollectorProfilePeer::COUNTRY, CollectorProfilePeer::ID, CollectorProfilePeer::TABLE_NAME, CollectorProfilePeer::COUNTRY, $max
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

    $collections = CollectionPeer::getRelatedCollections($object, $limit, $criteria);
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

    $snTotalItemAllowed = ($amSellerInfo['items_allowed'] < 0) ? $amSellerInfo['items_allowed'] : (int) $omSeller->getItemsAllowed() + $amSellerInfo['items_allowed'];
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

  /** added by Prakash Panchal 13-APR-2011
   * deductAllwedItems function.
   * return object
   * @deprecated
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

  /**
   * @static
   *
   * @param  array  $data
   * @return Collector|null
   */
  public static function saveUserDataFromArray($data = array())
  {
    $collector = new Collector();
    $collector->setUsername($data['username']);
    $collector->setPassword($data['password']);
    $collector->setDisplayName($data['display_name']);
    $collector->setEmail($data['email']);

    if (!empty($data['facebook_id']))
    {
      $collector->setFacebookId($data['facebook_id']);
    }

    $collector_profile = new CollectorProfile();
    $collector_profile->setCollector($collector);

    // Save new added fields as per collector and seller
    $collector->setWhatYouCollect($data['what_you_collect']);

    if (!empty($data['what_you_sell']))
    {
      $collector->setWhatYouSell($data['what_you_sell']);
    }
    if (!empty($data['what_you_collect']))
    {
      $collector->setWhatYouCollect($data['what_you_collect']);
      $collector_profile->setCollecting($data['what_you_collect']);
    }
    if (!empty($data['annually_spend']))
    {
      $collector->setAnnuallySpend($data['annually_spend']);
      $collector_profile->setAnuallySpent($data['annually_spend']);
    }
    if (!empty($data['most_expensive_item']))
    {
      $collector->setMostExpensiveItem($data['most_expensive_item']);
      $collector_profile->setMostSpent($data['most_expensive_item']);
    }
    if (!empty($data['company']))
    {
      $collector->setCompany($data['company']);
    }
    if (!empty($data['purchase_per_year']))
    {
      $collector->setPurchasesPerYear($data['purchase_per_year']);
    }
    // End save new fields
    // All of the profile data is optional, thus make sure to check it is provided

    if (!empty($data['collector_type']))
    {
      $collector_profile->setCollectorType($data['collector_type']);
    }
    if ($data['birthday']['month'] != '' && $data['birthday']['day'] != '' && $data['birthday']['year'] != '')
    {
      $collector_profile->setBirthday($data['birthday']);
    }
    if (!empty($data['gender']))
    {
      $collector_profile->setGender($data['gender']);
    }
    if (!empty($data['zip_postal']))
    {
      $collector_profile->setZipPostal($data['zip_postal']);
    }
    if (!empty($data['country']))
    {
      $collector_profile->setCountry($data['country']);
    }
    if (!empty($data['website']))
    {
      $collector_profile->setWebsite($data['website']);
    }

    $collector_profile->setPreferences(array(
      'show_age' => false, 'msg_on' => true, 'invite_only' => false
    ));

    $collector_profile->setNotifications(array(
      'comment' => true, 'buddy' => true, 'message' => true
    ));

    try
    {
      $collector_profile->save();
      $collector->save();

      // Send the profile data to Impermium to analyse
      $collector->sendToImpermium('CREATE');

      // Send the profile data to Defensio to analyse
      $collector->sendToDefensio('CREATE');
    }
    catch (PropelException $e)
    {
      echo $e->getMessage();
      return null;
    }

    return $collector;
  }

  public static function retrieveForSelect($q, $limit = 10)
  {
    $criteria = new Criteria();
    $criteria->clearSelectColumns();
    $criteria->addSelectColumn(self::ID);
    $criteria->addSelectColumn(self::DISPLAY_NAME);

    $criteria->add(self::DISPLAY_NAME, sprintf('%%%s%%', $q), Criteria::LIKE);

    return self::doSelectStmt($criteria)->fetchAll(PDO::FETCH_KEY_PAIR);
  }

}
