<?php

require 'lib/model/om/BaseCollection.php';

/**
 * IceTaggableBehavior
 *
 * @method array getTags($options = array())
 * @method boolean addTag($name)
 * @method boolean hasTag($name)
 */
class Collection extends BaseCollection
{
  public
    $_multimedia = array(),
    $_counts = array();

  public function postSave(PropelPDO $con = null)
  {
    parent::postSave($con);

    if ($con === null)
    {
      $con = Propel::getConnection(
        CollectionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE
      );
    }

    // Let's assume we can make the Collectible public
    $is_public = true;

    if (!$this->getName())
    {
      $is_public = false;
    }
    else if (!$this->getDescription())
    {
      $is_public = false;
    }

    // Update only if there is a change of the public status
    if ($is_public !== $this->getIsPublic())
    {
      $sql = sprintf(
        'UPDATE %s SET %s = %d WHERE %s = %d',
        CollectionPeer::TABLE_NAME, CollectionPeer::IS_PUBLIC, $is_public,
        CollectionPeer::ID, $this->getId()
      );
      $con->exec($sql);
    }
  }

  public function getTagString()
  {
    return implode(", ", $this->getTags());
  }

  public function getTagIds()
  {
    $c = new Criteria;
    $c->addSelectColumn(iceModelTaggingPeer::TAG_ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_ID, $this->getId());
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collection');
    $stmt = iceModelTaggingPeer::doSelectStmt($c);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getCollectibleIds($criteria = null, PropelPDO $con = null)
  {
    /** @var $q CollectibleQuery */
    $q = CollectibleQuery::create(null, $criteria)
       ->filterByCollection($this)
       ->joinWith('CollectionCollectible')
       ->orderBy('CollectionCollectible.Position', Criteria::ASC)
       ->orderBy('CollectionCollectible.CreatedAt', Criteria::ASC)
       ->setFormatter(ModelCriteria::FORMAT_STATEMENT)
       ->addSelectColumn('Id');

    /** @var $stmt PDOStatement */
    $stmt = $q->find();

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getCollectibles($criteria = null, PropelPDO $con = null)
  {
    /** @var $c Criteria */
    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();

    if (!$c->getOrderByColumns())
    {
      $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
      $c->addDescendingOrderByColumn(CollectiblePeer::CREATED_AT);
    }

    return parent::getCollectibles($c, $con);
  }

  public function getRandomCollectibles($limit = 10)
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn('RAND()');
    $c->setLimit($limit);

    return self::getCollectibles($c);
  }

  public function getRelatedCollections($limit = 5)
  {
    $collections = CollectorCollectionPeer::getRelatedCollections($this, $limit);

    if ($limit != $found = count($collections))
    {
      $limit = $limit - $found;

      /** @var $sf_context cqContext */
      $sf_context = cqContext::getInstance();

      /** @var $sf_user cqBaseUser */
      $sf_user = $sf_context->getUser();

      if ($sf_context && $sf_user->isAuthenticated())
      {
        /** @var $collector Collector */
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
      $c->add(CollectorCollectionPeer::ID, $this->getId(), Criteria::NOT_EQUAL);

      $collections = CollectorCollectionPeer::getRandomCollections($limit, $c);
      $rnd_flag = true;
    }

    return $collections;
  }

  public function getCountCollectibles()
  {
    return $this->countCollectibles();
  }

  public function countCollectiblesSince($date = null)
  {
    $date = null === $date ? new DateTime('7 day ago') : new DateTime($date);

    $c = new Criteria();
    $c->add(CollectiblePeer::CREATED_AT, $date, Criteria::GREATER_EQUAL);

    return $this->countCollectibles($c);
  }

  /**
   * @param  PropelPDO  $con
   * @return Collector
   */
  public function getCollector(PropelPDO $con = null)
  {
    $collector = null;

    if ($collector_collection = $this->getCollectorCollection($con))
    {
      $collector = $collector_collection->getCollector($con);
    }

    return $collector;
  }

  /**
   * @return integer
   */
  public function getCollectorId()
  {
    $id = null;

    if ($collector_collection = $this->getCollectorCollection())
    {
      $id = $collector_collection->getCollectorId();
    }

    return $id;
  }

  /**
   * Return the shipping rates for this collector, grouped by country
   *
   * @param     PropelPDO $con
   * @return    array
   *
   * @see       ShippingRateCollectorQuery::findAndGroupByCountryCode()
   */
  public function getShippingRatesByCountry(PropelPDO $con = null)
  {
    return ShippingRateCollectibleQuery::create()
      ->filterByCollectible($this)
      ->findAndGroupByCountryCode($con);
  }

  /**
   * Computes the value of the aggregate column num_items *
   * @param PropelPDO $con A connection object
   *
   * @return mixed The scalar result from the aggregate query
   */
  public function computeNumItems(PropelPDO $con = null)
  {
    $con = $con ?: Propel::getConnection();

    /** @var $stmt PDOStatement */
    $stmt = $con->prepare('
      SELECT COUNT(collectible_id)
        FROM `collection_collectible`
       WHERE collection_collectible.COLLECTION_ID = :p1
    ');
    $stmt->bindValue(':p1', $this->getId());
    $stmt->execute();

    return (int) $stmt->fetchColumn();
  }

  /**
   * Updates the aggregate column num_items *
   * @param PropelPDO $con A connection object
   */
  public function updateNumItems(PropelPDO $con = null)
  {
    $this->setNumItems($this->computeNumItems($con));
    $this->save($con);
  }

  public function getFeedTitle()
  {
    return $this->getName();
  }

  public function getFeedDescription()
  {
    $v = parent::getDescription();
    $v = trim(strip_tags($v));
    $v = cqStatic::truncateText($v, 255, '...', true);

    return $v;
  }

  /**
   * @param  PropelPDO  $con
   * @return boolean
   */
  public function preDelete(PropelPDO $con = null)
  {
    /** @var $collectibles CollectionCollectible[] */
    if ($collectibles = $this->getCollectionCollectibles())
    foreach ($collectibles as $collectible)
    {
      $collectible->delete($con);
    }

    /** @var $comments Comment[] */
    if ($comments = $this->getComments())
    foreach ($comments as $comment)
    {
      $comment->delete($con);
    }

    return parent::preDelete($con);
  }
}
