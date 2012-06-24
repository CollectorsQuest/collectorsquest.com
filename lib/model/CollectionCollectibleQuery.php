<?php

require 'lib/model/om/BaseCollectionCollectibleQuery.php';

class CollectionCollectibleQuery extends BaseCollectionCollectibleQuery
{
  /**
   * @param  string $v
   * @return CollectionCollectibleQuery
   */
  public function search($v)
  {
    return $this
      ->useCollectibleQuery()
        ->search(trim($v))
      ->endUse();
  }
}
