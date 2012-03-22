<?php

require 'lib/model/om/BaseCollectionCategoryPeer.php';

class CollectionCategoryPeer extends BaseCollectionCategoryPeer
{

  public static function getObjectForRoute($params)
  {
    return CollectionCategoryQuery::create()->findOneById($params['id']);
  }

  public static function retrieveForSelect($q, $limit = 10)
  {
    $collectionCategories = CollectionCategoryQuery::create()
        ->filterByName("%$q%")
        ->limit($limit)
        ->find()
        ->toKeyValue('Id', 'Name');

    return $collectionCategories;
  }
}
