<?php

require 'lib/model/om/BaseCollectionCategoryPeer.php';

class CollectionCategoryPeer extends BaseCollectionCategoryPeer
{
  public static function getObjectForRoute($params)
  {
    return CollectionCategoryQuery::create()->findOneById($params['id']);
  }
}
