<?php

class FrontendCollectorQuery extends CollectorQuery
{
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof FrontendCollectorQuery)
    {
      return $criteria
        ->filterById(15716, Criteria::NOT_EQUAL) // Frank's Picks
        ->filterByIsPublic(true);
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
    $query
      ->filterById(15716, Criteria::NOT_EQUAL) // Frank's Picks
      ->filterByIsPublic(true);

    return $query;
  }
}
