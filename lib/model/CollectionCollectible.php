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
}
