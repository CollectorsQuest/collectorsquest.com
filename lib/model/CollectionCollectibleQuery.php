<?php

require 'lib/model/om/BaseCollectionCollectibleQuery.php';

class CollectionCollectibleQuery extends BaseCollectionCollectibleQuery
{

  /**
   * Filter the query by a related Collector object
   *
   * @param     Collector|PropelCollection $collector The related object(s) to use as filter
   * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
   *
   * @return    CollectionCollectibleQuery The current query, for fluid interface
   */
  public function filterByCollector(Collector $collector, $comparison = null)
  {
    return $this
      ->useCollectibleQuery()
        ->filterByCollector($collector, $comparison)
      ->endUse()
    ;
  }

  public function isForSale()
  {
    return $this
      ->useCollectibleQuery()
        ->isForSale()
      ->endUse();
  }

  /**
   * @param  string $v
   * @return CollectionCollectibleQuery
   */
  public function search($v)
  {
    return $this
      ->useCollectibleQuery()
        ->search(trim($v))
      ->endUse();
  }
}
