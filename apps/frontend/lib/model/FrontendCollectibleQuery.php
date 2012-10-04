<?php

class FrontendCollectibleQuery extends CollectibleQuery
{
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof FrontendCollectibleQuery)
    {
      /**
       * By default we want to only show public Collectibles
       * which are part of a Collection
       */
      return $criteria
        ->filterByIsPublic(true)
        ->isPartOfCollection();
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
      ->filterByIsPublic(true)
      ->isPartOfCollection();

    return $query;
  }
}
