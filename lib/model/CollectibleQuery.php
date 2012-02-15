<?php

require 'lib/model/om/BaseCollectibleQuery.php';

class CollectibleQuery extends BaseCollectibleQuery
{
  public function filterByCollectionId($id, $comparison = null)
  {
    $this->addUsingAlias(CollectionCollectiblePeer::COLLECTION_ID, $id, $comparison);

    return $this;
  }
}
