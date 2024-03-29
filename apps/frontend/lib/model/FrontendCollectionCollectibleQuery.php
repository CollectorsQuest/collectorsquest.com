<?php

class FrontendCollectionCollectibleQuery extends CollectionCollectibleQuery
{
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof FrontendCollectionCollectibleQuery)
    {
      return $criteria
        ->joinWith('Collectible')
        ->_if(SF_ENV === 'prod' || SF_ENV === 'stg')
          ->isComplete()
        ->_endif();
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
      ->joinWith('Collectible')
      ->_if(SF_ENV === 'prod' || SF_ENV === 'stg')
        ->isComplete()
      ->_endif();

    return $query;
  }
}
