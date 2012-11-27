<?php

class FrontendCollectibleQuery extends CollectibleQuery
{
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof FrontendCollectibleQuery)
    {
      /**
       * By default we want to only show complete (public)
       * Collectibles which are part of a Collection
       */
      return $criteria
        ->isPartOfCollection()
        ->_if(SF_ENV === 'prod')
          ->isComplete()
        ->_endif();
    }

    $query = new FrontendCollectibleQuery();
    if (null !== $modelAlias)
    {
      $query->setModelAlias($modelAlias);
    }
    if ($criteria instanceof Criteria)
    {
      $query->mergeWith($criteria);
    }

    /**
     * By default we want to only show public Collectibles
     * which are part of a Collection
     */
    $query
      ->isPartOfCollection()
      ->_if(SF_ENV === 'prod')
        ->isComplete()
      ->_endif();

    return $query;
  }
}
