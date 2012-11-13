<?php

class FrontendCollectorQuery extends CollectorQuery
{
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof FrontendCollectorQuery)
    {
      return $criteria->filterByIsPublic(true);
    }

    $query = new FrontendCollectorQuery();
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
}
