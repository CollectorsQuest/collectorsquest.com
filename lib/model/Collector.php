<?php

require 'lib/model/om/BaseCollector.php';

/**
 * @method     int getSingupNumCompletedSteps() Return the number of completed signup steps
 * @method     Collector setSingupNumCompletedSteps(int $v) Set the number of completed signup steps
 * @method     Collector setCqnextAccessAllowed(boolean $v)
 *
 *
 * @method     Collector setSellerSettingsPaypalAccountId(string $v)
 * @method     string    getSellerSettingsPaypalAccountId()
 *
 * @method     Collector setSellerSettingsPaypalAccountStatus(string $v)
 * @method     string    getSellerSettingsPaypalAccountStatus()
 *
 * @method     Collector setSellerSettingsPaypalBusinessName(string $v)
 * @method     string    getSellerSettingsPaypalBusinessName()
 *
 * @method     Collector setSellerSettingsPaypalEmail(string $v)
 * @method     string    getSellerSettingsPaypalEmail()
 *
 * @method     Collector setSellerSettingsPaypalFirstName(string $v)
 * @method     string    getSellerSettingsPaypalFirstName()
 *
 * @method     Collector setSellerSettingsPaypalLastName(string $v)
 * @method     string    getSellerSettingsPaypalLastName()
 *
 *
 * @method     Collector setSellerSettingsPhoneCode(string $v)
 * @method     string    getSellerSettingsPhoneCode()
 *
 * @method     Collector setSellerSettingsPhoneNumber(string $v)
 * @method     string    getSellerSettingsPhoneNumber()
 *
 * @method     Collector setSellerSettingsPhoneExtension(string $v)
 * @method     string    getSellerSettingsPhoneExtension()
 *
 * @method     Collector setSellerSettingsStoreName(string $v)
 * @method     string    getSellerSettingsStoreName()
 *
 * @method     Collector setSellerSettingsStoreTitle(string $v)
 * @method     string    getSellerSettingsStoreTitle()
 *
 * @method     Collector setSellerSettingsStoreDescription(string $v)
 * @method     string    getSellerSettingsStoreDescription()
 *
 * @method     Collector setSellerSettingsReturnPolicy(string $v)
 * @method     string    getSellerSettingsReturnPolicy()
 *
 * @method     Collector setSellerSettingsPaymentAccepted(string $v)
 * @method     string    getSellerSettingsPaymentAccepted()
 *
 * @method     Collector setSellerSettingsWelcome(string $v)
 * @method     string    getSellerSettingsWelcome()
 *
 * @method     Collector setSellerSettingsShipping(string $v)
 * @method     string    getSellerSettingsShipping()
 *
 * @method     Collector setSellerSettingsRefunds(string $v)
 * @method     string    getSellerSettingsRefunds()
 *
 * @method     Collector setSellerSettingsAdditionalPolicies(string $v)
 * @method     string    getSellerSettingsAdditionalPolicies()
 *
 *
 * @method     Collector setVisitorInfoNumVisits(int $v)
 * @method     int       getVisitorInfoNumVisits()
 *
 * @method     Collector setVisitorInfoNumPageViews(int $v)
 * @method     int       getVisitorInfoNumPageViews()
 *
 *
 * @method     Collector setPreferencesShowAge(boolean $v)
 * @method     boolean   getPreferencesShowAge()
 *
 * @method     Collector setPreferencesMsgOn(boolean $v)
 * @method     boolean   getPreferencesMsgOn()
 *
 * @method     Collector setPreferencesInviteOnly(boolean $v)
 * @method     boolean   getPreferencesInviteOnly()
 *
 * @method     Collector setPreferencesNewsletter(boolean $v)
 * @method     boolean   getPreferencesNewsletter()
 *
 *
 * @method     Collector setNotificationsComment(boolean $v)
 * @method     boolean   getNotificationsComment()
 *
 * @method     Collector setNotificationsBuddy(boolean $v)
 * @method     boolean   getNotificationsBuddy()
 *
 * @method     Collector setNotificationsMessage(boolean $v)
 * @method     boolean   getNotificationsMessage()
 *
 * @method     Collector setTimeoutIgnoreForUser(boolean $v)
 * @method     boolean   getTimeoutIgnoreForUser()
 */
class Collector extends BaseCollector implements ShippingReferencesInterface
{
  /** @var array */
  public $_multimedia = array();

  /** @var array */
  public $_counts = array();

  /** @var array */
  protected $collCollectiblesInCollections;

  /** @var Collector */
  protected $seller;

  /**
   * Register extra properties to allow magic getters/setters to be used
   *
   * @see     ExtraPropertiesBehavior
   */
  public function initializeProperties()
  {
    $this->registerProperty('SINGUP_NUM_COMPLETED_STEPS', 1);
    $this->registerProperty(
      CollectorPeer::PROPERTY_CQNEXT_ACCESS_ALLOWED,
      CollectorPeer::PROPERTY_CQNEXT_ACCESS_ALLOWED_DEFAULT_VALUE
    );

    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PAYPAL_ACCOUNT_ID);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PAYPAL_ACCOUNT_STATUS);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PAYPAL_BUSINESS_NAME);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PAYPAL_EMAIL);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PAYPAL_FIRST_NAME);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PAYPAL_LAST_NAME);

    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PHONE_CODE);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PHONE_NUMBER);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PHONE_EXTENSION);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_STORE_NAME);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_STORE_TITLE);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_STORE_DESCRIPTION);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_RETURN_POLICY);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_PAYMENT_ACCEPTED);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_WELCOME);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_SHIPPING);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_REFUNDS);
    $this->registerProperty(CollectorPeer::PROPERTY_SELLER_SETTINGS_ADDITIONAL_POLICIES);

    $this->registerProperty(CollectorPeer::PROPERTY_VISITOR_INFO_FIRST_VISIT_AT);
    $this->registerProperty(CollectorPeer::PROPERTY_VISITOR_INFO_LAST_VISIT_AT);
    $this->registerProperty(CollectorPeer::PROPERTY_VISITOR_INFO_NUM_VISITS);
    $this->registerProperty(CollectorPeer::PROPERTY_VISITOR_INFO_NUM_PAGE_VIEWS);

    $this->registerProperty(CollectorPeer::PROPERTY_PREFERENCES_SHOW_AGE,
      CollectorPeer::PROPERTY_PREFERENCES_SHOW_AGE_DEFAULT);
    $this->registerProperty(CollectorPeer::PROPERTY_PREFERENCES_MSG_ON,
      CollectorPeer::PROPERTY_PREFERENCES_MSG_ON_DEFAULT);
    $this->registerProperty(CollectorPeer::PROPERTY_PREFERENCES_INVITE_ONLY,
      CollectorPeer::PROPERTY_PREFERENCES_INVITE_ONLY_DEFAULT);
    $this->registerProperty(CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER,
      CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER_DEFAULT);
    $this->registerProperty(CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER_OPT_OUT,
      CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER_OPT_OUT_DEFAULT);

    $this->registerProperty(CollectorPeer::PROPERTY_NOTIFICATIONS_COMMENT,
      CollectorPeer::PROPERTY_NOTIFICATIONS_COMMENT_DEFAULT);
    $this->registerProperty(CollectorPeer::PROPERTY_NOTIFICATIONS_BUDDY,
      CollectorPeer::PROPERTY_NOTIFICATIONS_BUDDY_DEFAULT);
    $this->registerProperty(CollectorPeer::PROPERTY_NOTIFICATIONS_MESSAGE,
      CollectorPeer::PROPERTY_NOTIFICATIONS_MESSAGE_DEFAULT);

    $this->registerProperty(CollectorPeer::PROPERTY_TIMEOUT_COMMENTS_AT);
    $this->registerProperty(CollectorPeer::PROPERTY_TIMEOUT_PRIVATE_MESSAGES_AT);

    $this->registerProperty(CollectorPeer::PROPERTY_TIMEOUT_IGNORE_FOR_USER, false);
  }

  /**
   * @param     mixed $v string, integer (timestamp), or DateTime value.
   *               Empty strings are treated as NULL.
   * @return    Collector The current object (for fluent API support)
   */
  public function setTimeoutCommentsAt($v)
  {
    $v = cqPropelTime::translateTimeToString($v);

    return parent::setTimeoutCommentsAt($v);
  }

  /**
   * @param     string $format The date/time format string (either date()-style or strftime()-style).
   *              If format is NULL, then the raw DateTime object will be returned.
   * @return    mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
   * @throws    RuntimeException - if unable to parse/validate the date/time value.
   */
  public function getTimeoutCommentsAt($format = 'Y-m-d H:i:s')
  {
    return cqPropelTime::format(
      parent::getTimeoutCommentsAt(),
      $format
    );
  }

  /**
   * @param     mixed $v string, integer (timestamp), or DateTime value.
   *               Empty strings are treated as NULL.
   * @return    Collector The current object (for fluent API support)
   */
  public function setTimeoutPrivateMessagesAt($v)
  {
    $v = cqPropelTime::translateTimeToString($v);

    return parent::setTimeoutPrivateMessagesAt($v);
  }

  /**
   * @param     string $format The date/time format string (either date()-style or strftime()-style).
   *              If format is NULL, then the raw DateTime object will be returned.
   * @return    mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
   * @throws    RuntimeException - if unable to parse/validate the date/time value.
   */
  public function getTimeoutPrivateMessagesAt($format = 'Y-m-d H:i:s')
  {
    return cqPropelTime::format(
      parent::getTimeoutPrivateMessagesAt(),
      $format
    );
  }

  /**
   * @param     mixed $v string, integer (timestamp), or DateTime value.
   *               Empty strings are treated as NULL.
   * @return    Collector The current object (for fluent API support)
   */
  public function setVisitorInfoFirstVisitAt($v)
  {
    $v = cqPropelTime::translateTimeToString($v);

    return parent::setVisitorInfoFirstVisitAt($v);
  }

  /**
   * @param     string $format  The date/time format string (either date()-style or strftime()-style).
   *                            If format is NULL, then the raw DateTime object will be returned.
   * @return    mixed  Formatted date/time value as string or DateTime object (if format is NULL),
   *                   NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
   * @throws    RuntimeException - if unable to parse/validate the date/time value.
   */
  public function getVisitorInfoFirstVisitAt($format = 'Y-m-d H:i:s')
  {
    return cqPropelTime::format(
      parent::getVisitorInfoFirstVisitAt(),
      $format
    );
  }

  /**
   * @param     mixed $v string, integer (timestamp), or DateTime value.
   *               Empty strings are treated as NULL.
   * @return    Collector The current object (for fluent API support)
   */
  public function setVisitorInfoLastVisitAt($v)
  {
    $v = cqPropelTime::translateTimeToString($v);

    return parent::setVisitorInfoLastVisitAt($v);
  }

  /**
   * @param     string $format  The date/time format string (either date()-style or strftime()-style).
   *                             If format is NULL, then the raw DateTime object will be returned.
   *
   * @return    mixed  Formatted date/time value as string or DateTime object (if format is NULL),
   *                   NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
   * @throws    RuntimeException - if unable to parse/validate the date/time value.
   */
  public function getVisitorInfoLastVisitAt($format = 'Y-m-d H:i:s')
  {
    return cqPropelTime::format(
      parent::getVisitorInfoLastVisitAt(),
      $format
    );
  }

  /**
   * Upon logging in, merge cookie data into ExtraProperties data
   *
   * @param     array $data
   * @return    Collector
   */
  public function mergeVisitorInfoArray($data)
  {
    foreach (CollectorPeer::$visitor_info_props as $prop_name)
    {
      if (isset($data[$prop_name]) && $value = $data[$prop_name])
      {
        switch ($prop_name)
        {
          case CollectorPeer::PROPERTY_VISITOR_INFO_FIRST_VISIT_AT:
            $new_time = strtotime($value);
            if ($new_time < $this->getVisitorInfoFirstVisitAt('U'))
            {
              $this->setVisitorInfoFirstVisitAt($new_time);
            }
            break;

          case CollectorPeer::PROPERTY_VISITOR_INFO_LAST_VISIT_AT:
            $new_time = strtotime($value);
            if ($new_time > $this->getVisitorInfoLastVisitAt('U'))
            {
              $this->setVisitorInfoFirstVisitAt($new_time);
            }
            break;

          case CollectorPeer::PROPERTY_VISITOR_INFO_NUM_PAGE_VIEWS:
            $this->setVisitorInfoNumPageViews(
              $this->getVisitorInfoNumPageViews() + $value
            );
            break;

          case CollectorPeer::PROPERTY_VISITOR_INFO_NUM_VISITS:
            $this->setVisitorInfoNumVisits(
              $this->getVisitorInfoNumVisits() + $value
            );
            break;

          default:
            $this->setProperty($prop_name, $value);
            break;
        }
      }
    }

    return $this;
  }

  /**
   * The collector has setup his paypal information
   *
   * @return    boolean
   */
  public function hasPayPalDetails()
  {
    return $this->getSellerSettingsPaypalEmail() &&
           $this->getSellerSettingsPaypalAccountStatus();
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

  /**
   * Temporary hardcoded to always true, will be updated in the near future
   *
   * @return boolean
   */
  public function getHasCompletedRegistration()
  {
    return true;
  }

  public function getGraphId()
  {
    $graph_id = parent::getGraphId();

    if (!$this->isNew() && null === $graph_id)
    {
      try
      {
        $client = cqStatic::getNeo4jClient();

        $node = $client->makeNode();
        $node->setProperty('model', 'Collector');
        $node->setProperty('model_id', $this->getId());
        $node->save();

        $graph_id = $node->getId();

        $this->setGraphId($node->getId());
        $this->save();
      }
      catch (Exception $e)
      {
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
    // Assume the User is not the owner if not an object
    if (!is_object($something))
    {
      return false;
    }

    // Special case for Multimedia objects
    if ($something instanceof iceModelMultimedia)
    {
      $something = $something->getModelObject();
    }

    if ($something instanceof PrivateMessage)
    {
      return $something->getSender() === $this->getId();
    }
    else if ($something instanceof ShoppingOrder)
    {
      return $something->getSellerId() === $this->getId();
    }
    else if (null === $something->getCollectorId())
    {
      // Nobody owns NULL
      return false;
    }
    else if (method_exists($something, 'getCollectorId'))
    {
      return $something->getCollectorId() === $this->getId();
    }

    return false;
  }

  public function getLastSeenAt($format = 'Y-m-d H:i:s')
  {
    $time = parent::getLastSeenAt($format);

    return ($time == '1999-11-30 00:00:00') ? null : $time;
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

  public function setDisplayName($v)
  {
    return parent::setDisplayName(trim(strip_tags($v)));
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
          '%s;%d;%s;%d', $version, $this->getId(), hash_hmac('sha1', base64_encode($json), $salt), $time
        );
        break;
    }

    return $hash;
  }

  /**
   * @param     PropelPDO  $con
   * @return    CollectorProfile
   */
  public function getProfile(PropelPDO $con = null)
  {
    return parent::getCollectorProfile($con);
  }

  /***
   * @param     CollectorProfile  $v
   * @return    Collector
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
      $sf_user = cqContext::getInstance()->getUser();

      if ($sf_user->isAuthenticated())
      {
        $collector = $sf_user->getCollector();
        $c = new Criteria();
        $c->add(CollectorCollectionPeer::ID, $this->getId(), Criteria::NOT_EQUAL);
        $c->add(CollectorCollectionPeer::COLLECTOR_ID, $collector->getId(), Criteria::NOT_EQUAL);
        $c->addAscendingOrderByColumn('RAND()');

        $collections = array_merge(
          $collections, CollectorCollectionPeer::getRelatedCollections($collector, $limit, $c)
        );
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

  public function countCollectionsWithCollectibles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
  {
    if ($criteria == null)
    {
      $criteria = new Criteria();
    }
    $criteria->add(CollectorCollectionPeer::NUM_ITEMS, 0, Criteria::GREATER_THAN);

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
      $tag_ids[] = (integer) $tag_id;
    }

    return $tag_ids;
  }

  /**
   * Return all tags as a string
   *
   * @return    string
   */
  public function getTagsString()
  {
    return implode(', ', array_merge(
      $this->getTags(array('is_triple' => false)),
      $this->getICollectTags(),
      $this->getISellTags()
    ));
  }

  /**
   * Return triple tags according to a namespace
   *
   * @param     string $ns
   * @param     string $key
   * @return    array
   */
  protected function getNamespacedTags($ns, $key)
  {
    return $this->getTags(array(
        'is_triple' => true,
        'namespace' => $ns,
        'key'       => $key,
        'return'    => 'value',
    ));
  }

  /**
   * Add a tag or tags for a namespace
   *
   * @param     string|array $tagname Anything that ::addTag() accepts
   * @param     string $ns
   * @param     string $key
   *
   * @return void
   */
  protected function addNamespacedTag($tagname, $ns, $key)
  {
    $tags = (array) IceTaggableToolkit::explodeTagString($tagname);

    $triple_prefix = sprintf('%s:%s=', $ns, $key);

    array_walk($tags, function(&$tag) use ($triple_prefix)
    {
      if (0 !== strpos($tag, $triple_prefix))
      {
        $tag = $triple_prefix.$tag;
      }
    });

    $this->addTag($tags);
  }

  /**
   * Set the tags for a specific namespace
   *
   * @param     string|array $tags Anything that ::addTag() accepts
   * @param     string $ns
   * @param     string $key
   *
   * @return    void
   */
  protected function setNamespacedTags($tags, $ns, $key)
  {
    $this->removeAllNamespacedTags($ns, $key);
    $this->addNamespacedTag($tags, $ns, $key);
  }

  /**
   * Remove all tags for a specific namespace
   *
   * @param     string  $ns
   * @param     string  $key
   *
   * @return    void
   */
  protected function removeAllNamespacedTags($ns, $key)
  {
    $this->removeTag($this->getTags(array(
        'is_triple' => true,
        'namespace' => $ns,
        'key'       => $key,
        'return'    => 'tag',
    )));
  }

  /**
   * Return tags for the I_COLLECT namespace
   *
   * @return    array
   */
  public function getICollectTags()
  {
    return $this->getNamespacedTags(
      CollectorPeer::TAGS_NAMESPACE_COLLECTOR,
      CollectorPeer::TAGS_KEY_I_COLLECT
    );
  }

  /**
   * Add tag or tags to the I_COLLECT namespace
   *
   * @param     string|array $tagname Anything that ::addTag() accepts
   */
  public function addICollectTag($tagname)
  {
    $this->addNamespacedTag(
      $tagname,
      CollectorPeer::TAGS_NAMESPACE_COLLECTOR,
      CollectorPeer::TAGS_KEY_I_COLLECT
    );
  }

  /**
   * Remove all tags for the I_COLLECT namespace
   */
  public function removeAllICollectTags()
  {
    $this->removeAllNamespacedTags(
      CollectorPeer::TAGS_NAMESPACE_COLLECTOR,
      CollectorPeer::TAGS_KEY_I_COLLECT
    );
  }

  /**
   * Set the tags for the I_COLLECT namespace
   *
   * @param     string|array $tags Anything that ::addTag() accepts
   */
  public function setICollectTags($tags)
  {
    $this->setNamespacedTags(
      $tags,
      CollectorPeer::TAGS_NAMESPACE_COLLECTOR,
      CollectorPeer::TAGS_KEY_I_COLLECT
    );
  }

  /**
   * Return a string representation of the I Collect tags
   *
   * @param     string $glue
   * @return    string
   */
  public function getICollect($glue = ', ')
  {
    return implode($glue, $this->getICollectTags());
  }

  /**
   * Return tags for the I_SELL namespace
   *
   * @return    array
   */
  public function getISellTags()
  {
    return $this->getNamespacedTags(
      CollectorPeer::TAGS_NAMESPACE_SELLER,
      CollectorPeer::TAGS_KEY_I_SELL
    );
  }

  /**
   * Add tag or tags to the I_SELL namespace
   *
   * @param     string|array $tagname Anything that ::addTag() accepts
   */
  public function addISellTag($tagname)
  {
    $this->addNamespacedTag(
      $tagname,
      CollectorPeer::TAGS_NAMESPACE_SELLER,
      CollectorPeer::TAGS_KEY_I_SELL
    );
  }

  /**
   * Remove all tags for the I_SELL namespace
   */
  public function removeAllISellTags()
  {
    $this->removeAllNamespacedTags(
      CollectorPeer::TAGS_NAMESPACE_SELLER,
      CollectorPeer::TAGS_KEY_I_SELL
    );
  }

  /**
   * Set the tags for the I_SELL namespace
   *
   * @param     string|array $tags Anything that ::addTag() accepts
   */
  public function setISellTags($tags)
  {
    $this->setNamespacedTags(
      $tags,
      CollectorPeer::TAGS_NAMESPACE_SELLER,
      CollectorPeer::TAGS_KEY_I_SELL
    );
  }

  /**
   * Return a string representation of the I Sell tags
   *
   * @param     string $glue
   * @return    string
   */
  public function getISell($glue = ', ')
  {
    return implode($glue, $this->getISellTags());
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
    if (null !== $this->collCollectiblesInCollections && !$overrideExisting)
    {
      return;
    }

    $this->collCollectiblesInCollections = new PropelObjectCollection();
    $this->collCollectiblesInCollections->setModel('Collectible');
  }

  /**
   * Get the collectibles related to this collector
   * which are assigned to collections
   *
   * @param     Criteria $criteria
   * @param     PropelPDO $con
   * @return    PropelObjectCollection Collectible[]
   */
  public function getCollectiblesInCollections(Criteria $criteria = null, PropelPDO $con = null)
  {
    if (null === $this->collCollectiblesInCollections || null !== $criteria)
    {
      if ($this->isNew() && null === $this->collCollectiblesInCollections)
      {
        // return empty collection
        $this->initCollectiblesInCollections();
      }
      else
      {
        $coll = CollectibleQuery::create(null, $criteria)
          ->filterByCollector($this)
          ->innerJoinCollectionCollectible()
          ->find($con);

        if (null !== $criteria)
        {
          return $coll;
        }
        $this->collCollectiblesInCollections = $coll;
      }
    }

    return $this->collCollectiblesInCollections;
  }

  /**
   * Count the number of collectibles related to this collector which
   * are assigned to collections
   *
   * @param     Criteria   $criteria
   * @param     boolean    $distinct
   * @param     PropelPDO  $con
   *
   * @return    integer
   */
  public function countCollectiblesInCollections(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
  {
    if (null === $this->collCollectiblesInCollections || null !== $criteria)
    {
      if ($this->isNew() && null === $this->collCollectiblesInCollections)
      {
        return 0;
      }
      else
      {
        $query = CollectibleQuery::create(null, $criteria);
        if ($distinct)
        {
          $query->distinct();
        }
        return $query
          ->filterByCollector($this)
          ->innerJoinCollectionCollectible()
          ->count($con);
      }
    }
    else
    {
      return count($this->collCollectiblesInCollections);
    }
  }

  /**
   * @see Collector::countCollectiblesInCollections()
   *
   * @param Criteria $criteria
   * @param bool $distinct
   * @param PropelPDO $con
   *
   * @return int
   */
  public function countCollectionCollectibles(
    Criteria $criteria = null,
    $distinct = false,
    PropelPDO $con = null
  ) {
    return $this->countCollectiblesInCollections($criteria, $distinct, $con);
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
    return $this->getId() ? new CollectionDropbox($this->getId()) : null;
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

  /**
   * Check if this collector has bought credits at any point in the past
   *
   * @return    boolean
   */
  public function hasBoughtCredits()
  {
    return (boolean) PackageTransactionQuery::create()
      ->filterByCollector($this)
      ->count();
  }

  /**
   * @return    boolean
   */
  public function getIsSeller()
  {
    return CollectorPeer::TYPE_SELLER == $this->getUserType();
  }

  /**
   * Get a Seller wrapped Collector
   *
   * @return    Seller|null
   */
  public function getSeller()
  {
    if ($this->getIsSeller())
    {
      if (null === $this->seller)
      {
        $this->seller = new Seller($this);
      }

      return $this->seller;
    }
    else
    {
      return null;
    }
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
      $params['referrer'] = $_SERVER['HTTP_REFERER'];
      $params['http-headers'] =
        'HTTP_ACCEPT_LANGUAGE: ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "\n" .
        'HTTP_REFERER: ' . $_SERVER['HTTP_REFERER'] . "\n" .
        'HTTP_ACCEPT_CHARSET: ' . @$_SERVER['HTTP_ACCEPT_CHARSET'] . "\n" .
        'HTTP_KEEP_ALIVE: ' . @$_SERVER['HTTP_KEEP_ALIVE'] . "\n" .
        'HTTP_ACCEPT_ENCODING: ' . $_SERVER['HTTP_ACCEPT_ENCODING'] . "\n" .
        'HTTP_CONNECTION: ' . $_SERVER['HTTP_CONNECTION'] . "\n" .
        'HTTP_ACCEPT: ' . $_SERVER['HTTP_ACCEPT'] . "\n" .
        'HTTP_USER_AGENT: ' . $_SERVER['HTTP_USER_AGENT'];
    }

    try
    {
      $defensio = cqStatic::getDefensioClient();
      $result = $defensio->postDocument($params);

      if (is_array($result) && intval($result[0]) == 200)
      {
        $this->setIsSpam(strval($result[1]->allow) == 'false' ? true : false);
        $this->setSpamScore(100 * strval($result[1]->spaminess));
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

    if (is_array($result) && intval($result[0]) == 200)
    {
      $this->setIsSpam((string) $result[1]->allow == 'false' ? true : false);
      $this->setSpamScore(100 * (float) $result[1]->spaminess);
      $this->setProperty('spam.signature', (string) $result[1]->signature);
      $this->setProperty('spam.classification', (string) $result[1]->classification);
      $this->setProperty('spam.profanity-match', 'false' == (string) $result[1]['profanity-match'] ? false : true);
      $this->setProperty('spam.allow', 'false' == (string) $result[1]['allow'] ? false : true);

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
   * Get the shipping references for this collector, grouped by country
   *
   * @param     PropelPDO $con
   * @return    array ShippingReference[]
   */
  public function getShippingReferencesByCountryCode(PropelPDO $con = null)
  {
    return ShippingReferenceQuery::create()
      ->filterByCollector($this)
      ->find($con)->getArrayCopy($keyColumn = 'CountryIso3166');
  }

  /**
   * Get the shipping reference for a specific country
   *
   * @param     string $coutry_code
   * @param     PropelPDO $con
   *
   * @return    ShippingReference
   */
  public function getShippingReferenceForCountryCode($coutry_code, PropelPDO $con = null)
  {
    return ShippingReferenceQuery::create()
      ->filterByCollector($this)
      ->filterByCountryIso3166($coutry_code)
      ->findOne($con)
    ?: ShippingReferenceQuery::create()
      ->filterByCollector($this)
      ->filterByCountryIso3166('ZZ') // international
      ->findOne($con);
  }

  /**
   * Get shipping rates for the collector's country
   *
   * @param     PropelPDO $con
   * @return    ShippingReference
   */
  public function getShippingReferenceDomestic(PropelPDO $con = null)
  {
    return $this->getShippingReferenceForCountryCode(
      $this->getProfile($con)->getCountryIso3166(), $con);
  }

  /**
   * @param  null|PropelPDO  $con
   * @return boolean
   */
  public function preDelete(PropelPDO $con = null)
  {
    // Delete shipping references manually, because no actual FK exists
    ShippingReferenceQuery::create()
      ->filterByCollector($this)
      ->delete($con);

    /** @var $collections Collection[] */
    if ($collections = $this->getCollections())
    {
      foreach ($collections as $collection)
      {
        $collection->delete($con);
      }
    }

    /** @var $comments Comment[] */
    if ($comments = $this->getComments())
    {
      foreach ($comments as $comment)
      {
        $comment->delete($con);
      }
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

  /**
   * For undefined methods, first try to find an appropriate method in the
   * CollectorProfile, after which fallback to Propel runtime behaviors
   *
   * @param     string $m
   * @param     array $a
   *
   * @return    mixed
   */
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

  /**
   * Assign a random avatar image to this collector
   *
   * @return    boolean Success?
   */
  public function assignRandomAvatar()
  {
    $avatar_id = CollectorPeer::$default_avatar_ids[array_rand(CollectorPeer::$default_avatar_ids)];
    $image = sfConfig::get('sf_web_dir')
      .'/images/frontend/multimedia/Collector/default/235x315/'. $avatar_id .'.jpg';

    /** @var $multimedia iceModelMultimedia */
    if ($multimedia = $this->setPhoto($image))
    {
      /**
       * We want to copy here optimized 100x100 thumb,
       * rather than the automatically generated one
       */
      $small = $multimedia->getAbsolutePath('100x100');
      copy(sfConfig::get('sf_web_dir')
        .'/images/frontend/multimedia/Collector/default/100x100/'. $avatar_id .'.jpg', $small);

      $this->getProfile()->setIsImageAuto(true);
      $this->getProfile()->save();

      return true;
    }

    return false;
  }

  public function getFeedTitle()
  {
    return $this->getDisplayName();
  }

  public function getFeedDescription()
  {
    return $this->getProfile()->getAboutMe();
  }

  /**
   * Returns the number of related FrontendCollectorCollection objects.
   *
   * @return int
   */
  public function countFrontendCollectorCollections()
  {
    return FrontendCollectorCollectionQuery::create()
      ->filterByCollector($this)
      ->count();
  }

  /**
   * @param  string  $rating
   * @return int
   */
  public function getFeedbackCount($rating = null)
  {
    $q = ShoppingOrderFeedbackQuery::create();
    $q->add(ShoppingOrderFeedbackPeer::IS_RATED, true);
    if ($rating)
    {
      $q->filterByRating($rating);
    }

    return $this->countShoppingOrderFeedbacksRelatedByBuyerId($q);
  }

  /**
   * Get positive feedback percentage
   * @return int
   */
  public function getPosFeedbackPercentage()
  {
    $criteria = new Criteria();
    $criteria->add(ShoppingOrderFeedbackPeer::IS_RATED, true);
    $total = $this->countShoppingOrderFeedbacksRelatedByBuyerId($criteria);

    $criteria->add(ShoppingOrderFeedbackPeer::RATING, ShoppingOrderFeedbackPeer::RATING_POSITIVE);
    $positive = $this->countShoppingOrderFeedbacksRelatedByBuyerId($criteria);

    return round(($positive / $total) * 100);
  }

  /**
   * Returns the number of related FrontendCollectorCollection objects.
   *
   * @return int
   */
  public function countFrontendCollectionCollectibles()
  {
    return FrontendCollectionCollectibleQuery::create()
      ->filterByCollector($this)
      ->count();
  }

}

sfPropelBehavior::add('Collector', array('IceMultimediaBehavior'));
sfPropelBehavior::add('Collector', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'Collector',
  array(
    'PropelActAsEblobBehavior' => array('column' => 'eblob')
  )
);
