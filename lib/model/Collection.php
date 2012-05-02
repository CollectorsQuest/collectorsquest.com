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

      /** @var $sf_context sfContext */
      $sf_context = sfContext::getInstance();

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

  public function hasThumbnail()
  {
    return MultimediaPeer::has($this, 'image', true);
  }

  public function getThumbnail()
  {
    return MultimediaPeer::get($this, 'image', true);
  }

  public function setThumbnail($file)
  {
    $c = new Criteria();
    $c->add(MultimediaPeer::MODEL, 'Collection');
    $c->add(MultimediaPeer::MODEL_ID, $this->getId());
    $c->add(MultimediaPeer::TYPE, 'image');
    $c->add(MultimediaPeer::IS_PRIMARY, true);

    MultimediaPeer::doDelete($c);

    if ($multimedia = MultimediaPeer::createMultimediaFromFile($this, $file))
    {
      $multimedia->setIsPrimary(true);
      $multimedia->makeThumb('50x50', 'shave');
      $multimedia->makeThumb('150x150', 'shave');
      $multimedia->makeThumb('190x150', 'shave', false);
      $multimedia->makeThumb('190x190', 'shave', false);
      $multimedia->save();

      return $multimedia;
    }

    return false;
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
   * @param  PropelPDO  $con
   * @return boolean
   */
  public function preDelete(PropelPDO $con = null)
  {
    /** @var $collectibles Collectible[] */
    if ($collectibles = $this->getCollectibles())
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

sfPropelBehavior::add('CollectorCollection', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'Collection',
  array('PropelActAsEblobBehavior' => array('column' => 'eblob')
));
