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
      return $criteria;
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

    // By default we want to only show public Collections
    $query->filterByIsPublic(true);

    return $query;
  }

  /**
   * @return FrontendCollectorCollectionQuery|CollectorCollectionQuery
   */
  public function hasCollectibles()
  {
    return parent::hasCollectibles()
      ->useCollectionCollectibleQuery()
        ->filterByIsPublic(true)
      ->endUse()
      ->groupBy('Id');
  }
}
