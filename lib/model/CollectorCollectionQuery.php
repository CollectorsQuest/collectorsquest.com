<?php

require 'lib/model/om/BaseCollectorCollectionQuery.php';

class CollectorCollectionQuery extends BaseCollectorCollectionQuery
{
  /**
   * @param ContentCategory $content_category
   * @param string $comparison
   *
   * @return CollectibleForSaleQuery
   */
  public function filterByContentCategoryWithDescendants($content_category, $comparison = null)
  {
    return $this
      ->useCollectionQuery()
        ->filterByContentCategory(
          ContentCategoryQuery::create()
            ->descendantsOfObjectIncluded($content_category)->find(),
          $comparison
        )
      ->endUse();
  }

  public function search($v)
  {
    return $this
      ->useCollectionQuery()
        ->filterByName('%'. $v .'%', Criteria::LIKE)
        ->_or()
        ->filterByDescription('%'. $v .'%', Criteria::LIKE)
      ->endUse();
  }
}
