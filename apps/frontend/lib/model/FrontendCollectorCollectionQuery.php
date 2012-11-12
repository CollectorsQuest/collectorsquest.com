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
        ->filterByCollectorId(15716, Criteria::NOT_EQUAL) // Frank's Picks
        ->filterByIsPublic(true)
        ->hasCollectibles();
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
      ->filterByCollectorId(15716, Criteria::NOT_EQUAL) // Frank's Picks
      ->filterByIsPublic(true)
      ->hasCollectibles();

    return $query;
  }

  /**
   * @return FrontendCollectorCollectionQuery|CollectorCollectionQuery
   */
  public function hasCollectibles()
  {
    return $this->hasPublicCollectibles();
  }
}
