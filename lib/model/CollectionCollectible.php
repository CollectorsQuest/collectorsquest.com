<?php

require 'lib/model/om/BaseCollectionCollectible.php';

class CollectionCollectible extends BaseCollectionCollectible
{

  public function preSave(PropelPDO $con = null)
  {
    /**
     * We need to place the new collectible at the end of the collection
     * but only if the object is new and position is not specified yet
     */
    if ($this->isNew() && !$this->isColumnModified(CollectionCollectiblePeer::POSITION))
    {
      $q = new CollectionCollectibleQuery();
      $q->addAsColumn('position', sprintf('MAX(%s)', CollectionCollectiblePeer::POSITION));
      $q->filterByCollectionId($this->getCollectionId());
      $q->setFormatter(ModelCriteria::FORMAT_STATEMENT);

      /** @var $stmt PDOStatement */
      $stmt = $q->find();

      $position = (int) $stmt->fetch(PDO::FETCH_COLUMN);
      $this->setPosition($position + 1);
    }

    return true;
  }

  public function postSave(PropelPDO $con = null)
  {
    parent::postSave($con);

    $this->updateRelatedCollection($con);
  }

  public function __toString()
  {
    return (string) $this->getCollectible();
  }

  /**
   * Update the aggregate column in the related Collection object
   *
   * @param PropelPDO $con A connection object
   */
  protected function updateRelatedCollection(PropelPDO $con = null)
  {
    if ($collection = $this->getCollection())
    {
      if ($collection->hasChildObject())
      {
        // this will update the parent as well
        $collection->getChildObject()->updateNumItems($con);
      }
      else
      {
        // childless collection, there shouldn't be any of those, but just in case
        $collection->updateNumItems($con);
      }
    }
  }

  public function getId()
  {
    return $this->getCollectibleId();
  }

  public function setId($v)
  {
    $this->setCollectibleId($v);
  }

  public function getCollectorId(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollectorId();
  }

  /**
   * Gets a single CollectorCollection object, which is related to this object by a one-to-one relationship.
   *
   * @param      PropelPDO $con optional connection object
   * @return     CollectorCollection
   * @throws     PropelException
   */
  public function getCollectorCollection(PropelPDO $con = null)
  {
    if ($this->singleCollectorCollection === null && !$this->isNew())
    {
      $this->singleCollectorCollection = CollectorCollectionQuery::create()->findPk($this->getCollectionId(), $con);
    }

    return $this->singleCollectorCollection;
  }

  /**
   * @see Collectible::getName()
   */
  public function getName()
  {
    return $this->getCollectible()->getName();
  }

  /**
   * @see Collectible::getDescription()
   */
  public function getDescription()
  {
    return call_user_func_array(
      array($this->getCollectible(), 'getDescription'),
      func_get_args()
    );
  }

  public function getPrimaryImage($mode = Propel::CONNECTION_READ)
  {
    return $this->getCollectible()->getPrimaryImage($mode);
  }

  public function getMultimedia($limit = 0, $type = null, $primary = null, $mode = Propel::CONNECTION_READ)
  {
    return $this->getCollectible()->getMultimedia($limit, $type, $primary, $mode);
  }

  public function getTags()
  {
    return $this->getCollectible()->getTags();
  }

  public function __call($m, $a)
  {
    $collectible = $this->getCollectible();

    if ($collectible instanceof Collectible && method_exists($collectible, $m))
    {
      return call_user_func_array(array($collectible, $m), $a);
    }
    else
    {
      return parent::__call($m, $a);
    }
  }

}
