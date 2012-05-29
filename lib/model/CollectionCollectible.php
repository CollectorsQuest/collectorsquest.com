<?php

require 'lib/model/om/BaseCollectionCollectible.php';

class CollectionCollectible extends BaseCollectionCollectible
{
  public function save(PropelPDO $con = null)
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

      if ($collection = $this->getCollection($con))
      {
        $q = CollectionCollectibleQuery::create()
           ->filterByCollectionId($this->getCollectionId());
        $num_items = $q->count($con);
        $collection->setNumItems($num_items);
        $collection->save();
      }
    }

    parent::save($con);
  }

  public function getId()
  {
    return $this->getCollectibleId();
  }

  public function setId($v)
  {
    $this->setCollectibleId($v);
  }

  public function getPrimaryImage($mode = Propel::CONNECTION_READ)
  {
    return $this->getCollectible()->getPrimaryImage($mode);
  }

  public function getMultimedia($limit = 0, $type = null, $primary = null, $mode = Propel::CONNECTION_READ)
  {
    return $this->getCollectible()->getMultimedia($limit, $type, $primary, $mode);
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
