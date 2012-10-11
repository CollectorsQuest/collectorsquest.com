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

  public function filterByIsPublic($isPublic = null, $comparison = null)
  {
    return $this
      ->useCollectibleQuery(null, Criteria::RIGHT_JOIN)
        ->filterByIsPublic($isPublic, $comparison)
      ->endUse();
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

  /**
   * @param ContentCategory|PropelCollection $content_category
   * @param string $comparison
   *
   * @return CollectionCollectibleQuery
   */
  public function filterByContentCategory($content_category, $comparison = null)
  {
    return $this
      ->useCollectionQuery()
        ->filterByContentCategory($content_category, $comparison)
      ->endUse();
  }

  /**
   * @param ContentCategory $content_category
   * @param string $comparison
   *
   * @return CollectionCollectibleQuery
   */
  public function filterByContentCategoryWithDescendants($content_category, $comparison = null)
  {
    return $this
      ->useCollectibleQuery()
        ->filterByContentCategoryWithDescendants($content_category, $comparison)
      ->endUse();
  }

}
