<?php

class FrontendCollectionCollectibleQuery extends CollectionCollectibleQuery
{
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof FrontendCollectionCollectibleQuery)
    {
      return $criteria
        ->filterByIsPublic(true)
        ->joinWith('Collectible');
    }

    $query = new FrontendCollectionCollectibleQuery();
    if (null !== $modelAlias)
    {
      $query->setModelAlias($modelAlias);
    }
    if ($criteria instanceof Criteria)
    {
      $query->mergeWith($criteria);
    }

    // By default we want to only show public Collections
    $query
      ->filterByIsPublic(true)
      ->joinWith('Collectible');

    return $query;
  }
}
