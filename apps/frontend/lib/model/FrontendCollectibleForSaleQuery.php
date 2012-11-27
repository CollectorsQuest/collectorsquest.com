<?php

class FrontendCollectibleForSaleQuery extends CollectibleForSaleQuery
{
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof FrontendCollectibleForSaleQuery)
    {
      /**
       * By default we want to only show public Collectibles
       * which are part of a Collection
       */
      return $criteria
        ->useCollectibleQuery()
          ->isComplete()
          ->isPartOfCollection()
        ->endUse()
        ->joinWith('Collectible');
    }

    $query = new FrontendCollectibleForSaleQuery();
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
      ->useCollectibleQuery()
        ->isComplete()
        ->isPartOfCollection()
      ->endUse()
      ->joinWith('Collectible');

    return $query;
  }
}
