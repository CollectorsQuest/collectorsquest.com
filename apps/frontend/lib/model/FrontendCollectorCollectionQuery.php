<?php

class FrontendCollectorCollectionQuery extends CollectorCollectionQuery
{
  /**
   * @param null $modelAlias
   * @param null $criteria
   *
   * @return CollectorCollectionQuery|FrontendCollectorCollectionQuery
   */
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof FrontendCollectorCollectionQuery)
    {
      /**
       * By default we want to only show public Collections
       * which have Collectibles asssigned to them
       */
      return $criteria
        ->filterByIsPublic(true)
        ->hasPublicCollectibles();
    }

    $query = new FrontendCollectorCollectionQuery();
    if (null !== $modelAlias)
    {
      $query->setModelAlias($modelAlias);
    }
    if ($criteria instanceof Criteria)
    {
      $query->mergeWith($criteria);
    }

    /**
     * By default we want to only show public Collections
     * which have Collectibles asssigned to them
     */
    $query
      ->filterByIsPublic(true)
      ->hasPublicCollectibles();

    return $query;
  }

}
