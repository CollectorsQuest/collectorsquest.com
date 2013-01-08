<?php

class CollectionDropbox extends CollectorCollection
{
  private $_collector_id = null;

  /**
   * @param integer $collector_id
   */
  public function __construct($collector_id = null)
  {
    $this->setCollectorId($collector_id);
  }

  /**
   * @return string
   */
  public function __toString()
  {
	return $this->getName();
  }

  public function getId()
  {
    return 0;
  }

  public function getGraphId($force = false, PropelPDO $con = null)
  {
    return null;
  }

  /**
   * @return integer | null
   */
  public function getCollectorId()
  {
    return $this->_collector_id;
  }

  public function setCollectorId($id)
  {
    $this->_collector_id = $id;
  }

  public function getCollector(PropelPDO $con = null, $doQuery = true)
  {
    if ($this->aCollector === null && ($this->collector_id !== null) && $doQuery)
    {
      $this->aCollector = CollectorQuery::create()->findPk($this->getCollectorId, $con);
    }

    return $this->aCollector;
  }

  public function getName()
  {
    return 'Dropbox';
  }

  public function getSlug()
  {
    return 'dropbox';
  }

  /**
   * @param  string  $type Can be 'html' or 'markdown'
   * @param  integer $limit
   *
   * @return string
   */
  public function getDescription($type = 'html', $limit = 0)
  {
    $description = trim('
      This is your Dropbox! You can upload your collectible images here
      before you decide which collection best fits them.
      You will be able to manage collectible in your Dropbox just like any other
      collectible in your other collections.'
    );

    return ($limit > 0) ? cqStatic::truncateText($description, $limit, true, '...') : $description;
  }

  public function getTagString()
  {
    return null;
  }

  public function getTagIds()
  {
    return null;
  }

  public function getCollectibleIds($criteria = null, PropelPDO $con = null)
  {
    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();

    $c->addSelectColumn(CollectiblePeer::ID);
    $c->add(CollectiblePeer::COLLECTOR_ID, $this->getCollectorId());
    $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN);
    $c->add(CollectionCollectiblePeer::COLLECTION_ID, null, Criteria::ISNULL);
    $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addAscendingOrderByColumn(CollectiblePeer::CREATED_AT);
    $stmt = CollectiblePeer::doSelectStmt($c, $con);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getCollectibles($criteria = null, PropelPDO $con = null)
  {
    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();

    $c->add(CollectiblePeer::COLLECTOR_ID, $this->getCollectorId(), Criteria::EQUAL);
    $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN);
    $c->add(CollectionCollectiblePeer::COLLECTION_ID, null, Criteria::ISNULL);

    $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addDescendingOrderByColumn(CollectiblePeer::CREATED_AT);

    if (null === $this->collCollectibles)
    {
      $collCollectibles = CollectibleQuery::create(null, $c)->find($con);

      if (null !== $criteria)
      {
        return $collCollectibles;
      }

      $this->collCollectibles = $collCollectibles;
    }

    return $this->collCollectibles;
  }

  public function getRandomCollectibles($limit = 10)
  {
    $c = new Criteria();
    $c->add(CollectiblePeer::COLLECTOR_ID, $this->getCollectorId());
    $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN);
    $c->add(CollectionCollectiblePeer::COLLECTION_ID, null, Criteria::ISNULL);
    $c->setLimit($limit);
    $c->addAscendingOrderByColumn('RAND()');

    return CollectiblePeer::doSelect($c);
  }

  public function countCollectibles($criteria = null, $distinct = false, PropelPDO $con = null)
  {
    if (null === $this->collCollectibles || null !== $criteria)
    {
      $query = CollectibleQuery::create(null, $criteria);

      if ($distinct)
      {
        $query->distinct();
      }

      return $query
        ->filterByCollectorId($this->getCollectorId(), Criteria::EQUAL)
        ->joinWith('CollectionCollectible', Criteria::LEFT_JOIN)
        ->useQuery('CollectionCollectible')
          ->filterBy('CollectionId', null, Criteria::ISNULL)
        ->endUse()
        ->count($con);
    }
    else
    {
      return count($this->collCollectibles);
    }
  }

  public function getRelatedCollections($limit = 5)
  {
    return null;
  }

  public function hasThumbnail()
  {
    return false;
  }

  public function getThumbnail()
  {
    return null;
  }

  public function setThumbnail($file)
  {
    return false;
  }

  public function getCountCollectibles()
  {
    return $this->countCollectibles();
  }

  public function countCollectiblesSince($date = null)
  {
    $date = null === $date ? new DateTime('7 day ago') : new DateTime($date);

    $c = new Criteria();
    $c->add(CollectiblePeer::COLLECTOR_ID, $this->getCollectorId());
    $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN);
    $c->add(CollectionCollectiblePeer::COLLECTION_ID, null, Criteria::ISNULL);
    $c->add(CollectiblePeer::CREATED_AT, $date, Criteria::GREATER_EQUAL);

    return CollectiblePeer::doCount($c);
  }

  public function save(PropelPDO $con = null)
  {
    return false;
  }

  public function preSave(PropelPDO $con = null)
  {
    return false;
  }

  public function delete(PropelPDO $con = null)
  {
    return false;
  }

  public function preDelete(PropelPDO $con = null)
  {
    return false;
  }
}
