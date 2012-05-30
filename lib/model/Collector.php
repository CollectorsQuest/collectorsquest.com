<?php

require 'lib/model/om/BaseCollector.php';

/**
 * @method     int getSingupNumCompletedSteps() Return the number of completed signup steps
 * @method     Collector setSingupNumCompletedSteps(int $v) Set the number of completed signup steps
 * @method     Collector setCqnextAccessAllowed(boolean $v)
 * @method     boolean getCqnextAccessAllowed()
 */
class Collector extends BaseCollector implements ShippingRatesInterface
{
  public
    $_multimedia = array(),
    $_counts = array();

  protected $collCollectiblesInCollections;

  public function initializeProperties()
  {
    $this->registerProperty('SINGUP_NUM_COMPLETED_STEPS', 1);
    $this->registerProperty(
      CollectorPeer::PROPERTY_CQNEXT_ACCESS_ALLOWED,
      CollectorPeer::PROPERTY_CQNEXT_ACCESS_ALLOWED_DEFAULT_VALUE
    );
  }

  /**
   * Property accessor. Always cast the return value to boolean
   *
   * @return    boolean
   */
  public function getCqnextAccessAllowed()
  {
    return (boolean) parent::getCqnextAccessAllowed();
  }

  public function getGraphId()
  {
    $graph_id = parent::getGraphId();

    if (!$this->isNew() && null === $graph_id)
    {
      try {
        $client = cqStatic::getNeo4jClient();

        $node = $client->makeNode();
        $node->setProperty('model', 'Collector');
        $node->setProperty('model_id', $this->getId());
        $node->save();

        $graph_id = $node->getId();

        $this->setGraphId($node->getId());
        $this->save();
      } catch (Exception $e) {
        // Error when trying to create a new neo4j node
      }
    }

    return $graph_id;
  }

  public function getCollectorId()
  {
    return $this->getId();
  }

  public function getCollectorSlug()
  {
    return $this->getSlug();
  }

  /**
   * @param  BaseObject $something
   * @return boolean
   */
  public function isOwnerOf($something)
  {
    // Special case for Multimedia objects
    if ($something instanceof iceModelMultimedia)
    {
      $something = $something->getModelObject();
    }

    if (is_object($something) && method_exists($something, 'getCollectorId'))
    {
      return $something->getCollectorId() == $this->getId();
    }
    else if ($something instanceof PrivateMessage)
    {
      return $something->getSender() == $this->getId();
    }

    return false;
  }

  public function getLastSeenAt($format = 'Y-m-d H:i:s')
  {
    $time = parent::getLastSeenAt($format);

    return ($time == "1999-11-30 00:00:00") ? null : $time;
  }

  /**
   * @param     string $password
   * @return    Collector
   */
  public function setPassword($password)
  {
    $this->setSha1Password(sha1($this->getSalt() . $password));

    return $this;
  }

  /**
   * Check if a password is valid for this collector
   *
   * @param     string $password
   * @return    boolean
   */
  public function checkPassword($password)
  {
    return sha1($this->getSalt() . $password) === $this->getSha1Password();
  }

  /**
   * @return    string
   */
  public function getDisplayName()
  {
    if (!$display_name = parent::getDisplayName())
    {
      $display_name = $this->getUsername();
    }

    return $display_name;
  }

  /**
   * Get the salt (generate it first if needed)
   *
   * @return    string
   */
  public function getSalt()
  {
    if (null === parent::getSalt())
    {
      $this->setSalt($this->generateSalt());
    }

    return parent::getSalt();
  }

  public function getAutoLoginHash($version = 'v1', $time = null, $salt = null)
  {
    $time = is_numeric($time) ? $time : time();
    $salt = !empty($salt) ? (string) $salt : $this->getSalt();

    switch ($version)
    {
      case 'v1':
      default:
        // Making sure the version is good value
        $version = 'v1';

        $json = json_encode(array(
          'version' => $version,
          'id'      => $this->getId(),
          'created' => (int) $this->getCreatedAt('U'),
          'time'    => (int) $time
        ));

        $hash = sprintf(
          "%s;%d;%s;%d", $version, $this->getId(), hash_hmac('sha1', base64_encode($json), $salt), $time
        );
        break;
    }

    return $hash;
  }

  /**
   * @return CollectorProfile
   */
  public function getProfile(PropelPDO $con = null)
  {
    return parent::getCollectorProfile($con);
  }

  /***
   * @return Collector
   */
  public function setProfile(CollectorProfile $v)
  {
    return parent::setCollectorProfile($v);
  }

  /**
   * Determines whether the collector as a prifile photo
   *
   * @return boolean
   */
  public function hasPhoto()
  {
    return $this->getPrimaryImage() ? true : false;
  }

  /**
   * Returns the multimedia object for the collector profile photo
   *
   * @return Multimedia
   */
  public function getPhoto()
  {
    return $this->getPrimaryImage();
  }

  public function setPhoto($file)
  {
    return $this->setPrimaryImage($file);
  }

  public function getMessagesCount()
  {
    $c = new Criteria();
    $c->add(PrivateMessagePeer::RECEIVER, $this->getId());
    $c->add(PrivateMessagePeer::IS_DELETED, false);
    $c->addGroupByColumn(PrivateMessagePeer::THREAD);
    $c->addDescendingOrderByColumn(PrivateMessagePeer::CREATED_AT);

    return PrivateMessagePeer::doCount($c);
  }

  public function getReadMessagesCount()
  {
    $c = new Criteria();
    $c->add(PrivateMessagePeer::RECEIVER, $this->getId());
    $c->add(PrivateMessagePeer::IS_READ, true);
    $c->add(PrivateMessagePeer::IS_DELETED, false);
    $c->addGroupByColumn(PrivateMessagePeer::THREAD);
    $c->addDescendingOrderByColumn(PrivateMessagePeer::CREATED_AT);

    return PrivateMessagePeer::doCount($c);
  }

  public function getUnreadMessagesCount()
  {
    $c = new Criteria();
    $c->add(PrivateMessagePeer::RECEIVER, $this->getId());
    $c->add(PrivateMessagePeer::IS_READ, false);
    $c->add(PrivateMessagePeer::IS_DELETED, false);
    $c->addGroupByColumn(PrivateMessagePeer::THREAD);
    $c->addDescendingOrderByColumn(PrivateMessagePeer::CREATED_AT);

    return PrivateMessagePeer::doCount($c);
  }

  public function getCollectionCategoryIds($criteria = null, PropelPDO $con = null)
  {
    $c = $criteria instanceof Criteria ? clone $criteria : new Criteria();

    $c->addSelectColumn(CollectorCollectionPeer::COLLECTION_CATEGORY_ID);
    $c->add(CollectorCollectionPeer::COLLECTOR_ID, $this->getId());
    $stmt = CollectorCollectionPeer::doSelectStmt($c, $con);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getRelatedCollections($limit = 10)
  {
    $collections = CollectorCollectionPeer::getRelatedCollections($this, $limit);

    if ($limit != $found = count($collections))
    {
      $limit = $limit - $found;

      /** @var $sf_user cqBaseUser */
      $sf_user = sfContext::getInstance()->getUser();

      if ($sf_user->isAuthenticated())
      {
        $collector = $sf_user->getCollector();
        $c = new Criteria();
        $c->add(CollectorCollectionPeer::ID, $this->getId(), Criteria::NOT_EQUAL);
        $c->add(CollectorCollectionPeer::COLLECTOR_ID, $collector->getId(), Criteria::NOT_EQUAL);
        $c->addAscendingOrderByColumn('RAND()');

        $collections = array_merge($collections, CollectorCollectionPeer::getRelatedCollections($collector, $limit, $c));
      }
    }

    if (0 == count($collections))
    {
      $c = new Criteria();
      $c->add(CollectorCollectionPeer::COLLECTOR_ID, $this->getId(), Criteria::NOT_EQUAL);

      $collections = CollectorCollectionPeer::getRandomCollections($limit, $c);
    }

    return $collections;
  }

  public function getRecentCollections($limit = 2)
  {
    $c = new Criteria();
    $c->add(CollectorCollectionPeer::COLLECTOR_ID, $this->getId());
    $c->addDescendingOrderByColumn(CollectorCollectionPeer::UPDATED_AT);
    $c->setLimit($limit);

    $collections = CollectorCollectionPeer::doSelect($c);

    return $collections;
  }

  public function getCollections($criteria = null, PropelPDO $con = null)
  {
    return $this->getCollectorCollections($criteria, $con);
  }

  public function getCollectionIds($criteria = null, PropelPDO $con = null)
  {
    $c = $criteria instanceof Criteria ? clone $criteria : new Criteria();

    $c->addSelectColumn(CollectorCollectionPeer::ID);
    $c->add(CollectorCollectionPeer::COLLECTOR_ID, $this->getId());
    $stmt = CollectorCollectionPeer::doSelectStmt($c, $con);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function countCollections(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
  {
    return $this->countCollectorCollections($criteria, $distinct, $con);
  }

  public function getTagIds()
  {
    $c = new Criteria;
    $c->addSelectColumn(iceModelTaggingPeer::TAG_ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_ID, $this->getId());
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collection');

    $stmt = iceModelTaggingPeer::doSelectStmt($c);
    $tag_ids = array();
    while ($tag_id = $stmt->fetchColumn(0))
    {
      $tag_ids[] = (int)$tag_id;
    }

    return $tag_ids;
  }

  public function getTerms()
  {
    return TermPeer::getTerms($this);
  }

  public function getCountCollections()
  {
    return $this->countCollections();
  }

  public function getCountCollectibles()
  {
    return $this->countCollectibles();
  }

  /**
   * Clear the collCollectiblesInCollections collection
   *
   * @return    void
   */
  public function clearCollectiblesInCollections()
  {
    // important to set this to NULL since that means it is uninitialized
    $this->collCollectiblesInCollections = null;
  }

  /**
   * Initializes the collCollectiblesInCollections collection.
   *
   * @param     boolean $overrideExisting
   * @return    void
   */
  public function initCollectiblesInCollections($overrideExisting = true)
  {
    if (null !== $this->collCollectiblesInCollections && !$overrideExisting) {
      return;
    }
    $this->collCollectiblesInCollections = new PropelObjectCollection();
    $this->collCollectiblesInCollections->setModel('Collectible');
  }

  /**
   * Get the collectibles related to this collector that are asigned in
   * collections
   *
   * @param     Criteria $criteria
   * @param     PropelPDO $con
   * @return    PropelObjectCollection Collectible[]
   */
  public function getCollectiblesInCollections(
    Criteria $criteria = null,
    PropelPDO $con = null
  ) {
    if (null === $this->collCollectiblesInCollections || null !== $criteria) {
      if ($this->isNew() && null === $this->collCollectiblesInCollections) {
        // return empty collection
        $this->initCollectiblesInCollections();
      } else {
        $coll = CollectibleQuery::create(null, $criteria)
          ->filterByCollector($this)
          ->innerJoinCollectionCollectible()
          ->find($con);
        if (null !== $criteria) {
          return $coll;
        }
        $this->collCollectiblesInCollections = $coll;
      }
    }
    return $this->collCollectiblesInCollections;
  }

  /**
   * Count the number of collectibles related to this collector that
   * are asigned in collections
   *
   * @param     Criteria $criteria
   * @param     boolean $distinct
   * @param     PropelPDO $con
   * @return    integer
   */
  public function countCollectiblesInCollections(
    Criteria $criteria = null,
    $distinct = false,
    PropelPDO $con = null
  ) {
    if (null === $this->collCollectiblesInCollections || null !== $criteria) {
      if ($this->isNew() && null === $this->collCollectiblesInCollections) {
        return 0;
      } else {
        $query = CollectibleQuery::create(null, $criteria);
        if($distinct) {
          $query->distinct();
        }
        return $query
          ->filterByCollector($this)
          ->innerJoinCollectionCollectible()
          ->count($con);
      }
    } else {
      return count($this->collCollectiblesInCollections);
    }
  }

  public function getCollectorFriends(Criteria $criteria = null)
  {
    $c = ($criteria !== null) ? clone $criteria : new Criteria();
    $c->add(CollectorFriendPeer::COLLECTOR_ID, $this->getId());
    $c->addJoin(CollectorPeer::ID, CollectorFriendPeer::FRIEND_ID);

    return CollectorPeer::doSelect($c);
  }

  public function getCollectionDropbox()
  {
    return !$this->isNew() ? new CollectionDropbox($this->getId()) : null;
  }

  public function hasFacebook()
  {
    $uid = $this->getFacebookId();

    return is_numeric($uid);
  }

  public function isFacebookOnly()
  {
    return ($this->hasFacebook() && preg_match('/^fb(\d+)$/', $this->getUsername()));
  }

  public function getIsSeller()
  {
    return $this->getUserType() == 'Seller';
  }

  public function fromArray($array, $keyType = BasePeer::TYPE_PHPNAME)
  {
    parent::fromArray($array, $keyType);

    if (!empty($array['password']))
    {
      $this->setPassword($array['password']);
    }
    if (isset($array['photo']))
    {
      $this->setPhoto($array['photo']);
    }
  }

  public function getLastCollectorGeocache()
  {
    $criteria = new Criteria();
    $criteria->addDescendingOrderByColumn(CollectorGeocachePeer::ID);

    return CollectorGeocachePeer::doSelectOne($criteria);
  }

  public function sendToDefensio($operation = 'UPDATE')
  {
    $content = implode(' ', array(
      $this->getProfile()->getAboutMe(),
      $this->getProfile()->getAboutWhatYouCollect(),
      $this->getProfile()->getAboutCollections(),
      $this->getProfile()->getAboutInterests()
    ));

    $params = array(
      'platform'           => 'website',
      'type'               => 'other',
      'author-email'       => $this->getEmail(),
      'author-logged-in'   => ($operation == 'UPDATE') ? 'true' : 'false',
      'author-name'        => $this->getDisplayName(),
      'author-url'         => $this->getProfile()->getWebsiteUrl(),
      'document-permalink' => 'http://www.collectorsquest.com/collector/' . $this->getId() . '/' . $this->getSlug(),
      'content'            => $content,
      'async'              => 'false'
    );

    if (php_sapi_name() !== 'cli')
    {
      $params['author-ip'] = IceStatic::getUserIpAddress();
      $params['referrer'] = $_SERVER["HTTP_REFERER"];
      $params['http-headers'] =
          "HTTP_ACCEPT_LANGUAGE: " . $_SERVER["HTTP_ACCEPT_LANGUAGE"] . "\n" .
              "HTTP_REFERER: " . $_SERVER["HTTP_REFERER"] . "\n" .
              "HTTP_ACCEPT_CHARSET: " . @$_SERVER["HTTP_ACCEPT_CHARSET"] . "\n" .
              "HTTP_KEEP_ALIVE: " . @$_SERVER["HTTP_KEEP_ALIVE"] . "\n" .
              "HTTP_ACCEPT_ENCODING: " . $_SERVER["HTTP_ACCEPT_ENCODING"] . "\n" .
              "HTTP_CONNECTION: " . $_SERVER["HTTP_CONNECTION"] . "\n" .
              "HTTP_ACCEPT: " . $_SERVER["HTTP_ACCEPT"] . "\n" .
              "HTTP_USER_AGENT: " . $_SERVER["HTTP_USER_AGENT"];
    }

    try
    {
      $defensio = cqStatic::getDefensioClient();
      $result = $defensio->postDocument($params);

      if (is_array($result) && (int)$result[0] == 200)
      {
        $this->setIsSpam((string)$result[1]->allow == 'false' ? true : false);
        $this->setSpamScore(100 * (float)$result[1]->spaminess);
        $this->setProperty('spam.signature', $result[1]->signature);
        $this->setProperty('spam.classification', $result[1]->classification);
        $this->setProperty('spam.profanity-match', 'false' == $result[1]['profanity-match'] ? false : true);
        $this->setProperty('spam.allow', 'false' == $result[1]['allow'] ? false : true);
        $this->save();
      }
    }
    catch (Exception $e)
    {
      $this->log($e->getMessage(), Propel::LOG_WARNING);
    }

  }

  public function markAsSpam()
  {
    $this->setIsSpam(true);
    $this->setSpamScore(100);
    $this->save();

    $this->sendToDefensioMark(false);

    return $this;
  }

  public function markAsHam()
  {
    $this->setIsSpam(false);
    $this->setSpamScore(5);
    $this->save();

    $this->sendToDefensioMark(true);

    return $this;
  }

  /**
   * Send to defensio mark as spam|ham
   *
   * @param  boolean  $allow
   * @return boolean
   */
  public function sendToDefensioMark($allow)
  {
    $params = array(
      'allow'     => $allow ? 'true' : 'false',
    );

    try
    {
      $defensio = cqStatic::getDefensioClient();
      $result = $defensio->putDocument($this->getProperty('spam.signature'), $params);
    }
    catch (DefensioError $e)
    {
      $result = null;
    }

    if (is_array($result) && (int)$result[0] == 200)
    {
      $this->setIsSpam((string)$result[1]->allow == 'false' ? true : false);
      $this->setSpamScore(100 * (float)$result[1]->spaminess);
      $this->setProperty('spam.signature', (string)$result[1]->signature);
      $this->setProperty('spam.classification', (string)$result[1]->classification);
      $this->setProperty('spam.profanity-match', 'false' == (string)$result[1]['profanity-match'] ? false : true);
      $this->setProperty('spam.allow', 'false' == (string)$result[1]['allow'] ? false : true);

      return true;
    }

    return false;
  }

  /**
   * @param string $action One of the ['follows', 'likes', 'owns', 'blocks']
   * @param BaseObject $model
   *
   * @return boolean
   */
  public function graph($action = 'follows', BaseObject $model = null)
  {
    $client = cqStatic::getNeo4jClient();

    if ($model !== null && method_exists($model, 'getGraphId'))
    {
      $active = $client->getNode($this->getGraphId());
      $passive = $client->getNode($model->getGraphId());

      try
      {
        return $active->relateTo($passive, $action)->save();
      }
      catch (\Everyman\Neo4j\Exception $e)
      {
        ;
      }
    }

    return false;
  }

  /**
   * Get the shipping rates for this collector, grouped by country
   *
   * @param     PropelPDO $con
   * @return    array
   *
   * @see       ShippingRateCollectorQuery::findAndGroupByCountryCode()
   */
  public function getShippingRatesGroupedByCountryCode(PropelPDO $con = null)
  {
    return ShippingRateCollectorQuery::create()
      ->filterByCollector($this)
      ->findAndGroupByCountryCode($con);
  }

  /**
   * Get shipping rates for a specific country
   *
   * @param     string $coutry_code
   * @param     PropelPDO $con
   * @return    ShippingRate[]
   */
  public function getShippingRatesForCountryCode($coutry_code, PropelPDO $con = null)
  {
    return ShippingRateCollectorQuery::create()
      ->filterByCollector($this)
      ->filterByCountryIso3166($coutry_code)
      ->find($con);
  }

  /**
   * Get shipping rates for the collector's country
   *
   * @param     PropelPDO $con
   * @return    ShippingRate[]
   */
  public function getShippingRatesDomestic(PropelPDO $con = null)
  {
    return ShippingRateCollectorQuery::create()
      ->filterByCollector($this)
      ->filterByCountryIso3166($this->getProfile()->getCountryIso3166())
      ->find($con);
  }

  /**
   * Return the domestic country code
   *
   * @return    string
   */
  public function getDomesticCountryCode()
  {
    return $this->getProfile()->getCountryIso3166();
  }

  /**
   * @param  null|PropelPDO  $con
   * @return boolean
   */
  public function preDelete(PropelPDO $con = null)
  {
    /** @var $collections Collection[] */
    if ($collections = $this->getCollections())
      foreach ($collections as $collection)
      {
        $collection->delete($con);
      }

    /** @var $collectible_offers CollectibleOffer[] */
    if ($collectible_offers = $this->getCollectibleOffers())
      foreach ($collectible_offers as $collectible_offer)
      {
        $collectible_offer->delete($con);
      }

    /** @var $comments Comment[] */
    if ($comments = $this->getComments())
      foreach ($comments as $comment)
      {
        $comment->delete($con);
      }

    // Deleting private messages
    $c = new Criteria();
    $c->add(CollectorPeer::ID, PrivateMessagePeer::RECEIVER);
    $c->addOr(CollectorPeer::ID, PrivateMessagePeer::SENDER);

    /** @var $messages PrivateMessage[] */
    $messages = PrivateMessagePeer::doSelect($c);
    if (!empty($messages))
    {
      foreach ($messages as $message)
      {
        $message->setIsDeleted(true);
        $message->save($con);
      }
    }

    /** @var $collector_identifiers CollectorIdentifier[] */
    if ($collector_identifiers = $this->getCollectorIdentifiers($con))
    {
      foreach ($collector_identifiers as $collector_identifier)
      {
        $collector_identifier->delete($con);
      }
    }

    /** @var $collector_geocaches CollectorGeoCache[] */
    if ($collector_geocaches = $this->getCollectorGeocaches($con))
    {
      foreach ($collector_geocaches as $collector_geocache)
      {
        $collector_geocache->delete($con);
      }
    }

    return parent::preDelete($con);
  }

  public function __call($m, $a)
  {
    $profile = $this->getProfile();

    if ($profile instanceof CollectorProfile && method_exists($profile, $m))
    {
      return call_user_func_array(array($profile, $m), $a);
    }
    else
    {
      return parent::__call($m, $a);
    }
  }

  public function generateSalt()
  {
    return md5($this->getUsername() . '_' . cqStatic::getUniqueId());
  }

  public function getLastEmailChangeRequest($verified = false)
  {
    return CollectorEmailPeer::retrieveLastPending($this, $verified);
  }

  /**
   * Sets new limit of max collectibles for sale
   *
   * @param $collectiblesForSale
   * @return Collector
   * @todo add tests
   */
  public function addCollectiblesForSaleLimit($collectiblesForSale)
  {
    $newLimit = $collectiblesForSale < 0 ? 10000 : ($this->getItemsAllowed() + $collectiblesForSale);
    $this->setItemsAllowed($newLimit);
    $this->setMaxCollectiblesForSale($newLimit);

    return $this;
  }

  /**
   * Recalculates max collectibles for sale based on currently active packages
   *
   * @return Collector
   *
   * @todo add tests
   */
  public function updateCollectiblesForSaleLimit()
  {
    /* @var $activePackageTransactions PackageTransaction[] */
    $activePackageTransactions = PackageTransactionQuery::create()
        ->filterByCollector($this)
        ->filterByExpiryDate(time(), Criteria::GREATER_THAN)
        ->find()
        ;

    $collectiblesForSale = 0;
    foreach ($activePackageTransactions as $packageTransaction)
    {
      if ($packageTransaction->getMaxItemsForSale() < 0)
      {
        $collectiblesForSale = 10000;
        break;
      }
      $collectiblesForSale += $packageTransaction->getMaxItemsForSale();
    }

    $this->setMaxCollectiblesForSale($collectiblesForSale);

    return $this;
  }


  /**
   * For each Multimedia that is added to the Advert, this method will be called
   * to take care of creating the right thumnail sizes
   *
   * @param  iceModelMultimedia  $multimedia
   * @param  array $options
   *
   * @throws InvalidArgumentException
   * @return void
   */
  public function createMultimediaThumbs(iceModelMultimedia $multimedia, $options = array())
  {
    $watermark = isset($options['watermark']) ? (boolean) $options['watermark'] : false;

    $multimedia->makeThumb(100, 100, 'center', false);
    $multimedia->makeCustomThumb(235, 315, '235x315', 'top', $watermark);
  }

}

sfPropelBehavior::add('Collector', array('IceMultimediaBehavior'));

sfPropelBehavior::add(
  'Collector',
  array(
    'PropelActAsEblobBehavior' => array('column' => 'eblob')
  ));

sfPropelBehavior::add(
  'Collector', array(
    'PropelActAsSluggableBehavior' => array(
      'columns'   => array(
        'from' => CollectorPeer::DISPLAY_NAME,
        'to'   => CollectorPeer::SLUG
      ),
      'separator' => '-',
      'permanent' => false,
      'lowercase' => true,
      'ascii'     => true,
      'chars'     => 64
    )
  )
);
