<?php

require 'lib/model/om/BaseContentCategoryQuery.php';

class ContentCategoryQuery extends BaseContentCategoryQuery
{

  /**
   * Return only content categories that are direct descendants of the root node
   *
   * @return    ContentCategoryQuery
   */
  public function childrenOfRoot()
  {
    return $this
      ->filterByTreeLevel(1);
  }

  /**
   * Exclude Root from the query
   *
   * @return    ContentCategoryQuery
   */
  public function notRoot()
  {
    return $this
      ->filterByTreeLevel(0, Criteria::NOT_EQUAL);
  }

  /**
   * Filter on only categories that have a related collectible for sale
   *
   * @return    ContentCategoryQuery
   */
  public function hasCollectiblesForSale()
  {
    return $this
      ->useCollectionQuery()
        ->useCollectionCollectibleQuery()
          ->isForSale()
        ->endUse()
      ->endUse()
      ->groupBy('Id');
  }

  /**
   * Filter on only categories that have a related collection
   *
   * @return    ContentCategoryQuery
   */
  public function hasCollections()
  {
    return $this
      ->innerJoinCollectorCollection()
      ->groupBy('Id');
  }

  public function hasCollectionsWithCollectibles()
  {
    return $this
      ->useCollectorCollectionQuery(null, Criteria::INNER_JOIN)
        ->hasCollectibles()
      ->endUse()
      ->groupBy('Id');
  }

  /**
   * Filter the query to restrict the result to descendants of an object, and the
   * object itself
   *
   * @param     ContentCategory $contentCategory The object to use for descendant search
   *
   * @return    ContentCategoryQuery The current query, for fluid interface
   */
  public function descendantsOfObjectIncluded(ContentCategory $contentCategory)
  {
    return $this
      ->addUsingAlias(ContentCategoryPeer::LEFT_COL, $contentCategory->getLeftValue(), Criteria::GREATER_EQUAL)
      ->addUsingAlias(ContentCategoryPeer::LEFT_COL, $contentCategory->getRightValue(), Criteria::LESS_EQUAL);
  }

  /**
   * Filter the query to restrict the result to ancestors of an object, and the
   * object itself
   *
   * @param     ContentCategory $contentCategory The object to use for ascendants search
   *
   * @return    ContentCategoryQuery The current query, for fluid interface
   */
  public function ancestorsOfObjectIncluded(ContentCategory $contentCategory)
  {
    return $this
      ->addUsingAlias(ContentCategoryPeer::LEFT_COL, $contentCategory->getLeftValue(), Criteria::LESS_EQUAL)
      ->addUsingAlias(ContentCategoryPeer::RIGHT_COL, $contentCategory->getRightValue(), Criteria::GREATER_EQUAL);
  }

  public function filterByLevel($level = null, $comparison = null)
  {
    return $this->filterByTreeLevel($level, $comparison);
  }

}
