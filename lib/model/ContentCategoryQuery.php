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
      ->useCollectionQuery(null, Criteria::INNER_JOIN)
        ->useCollectionCollectibleQuery()
          ->isForSale()
        ->endUse()
      ->endUse()
      ->groupById();
  }

  /**
   * Filter on only categories that have a related collection
   *
   * @return    ContentCategoryQuery
   */
  public function hasCollections()
  {
    return $this
      ->where(sprintf(
        'EXISTS (SELECT 1 FROM %s WHERE %s = %s)',
        CollectorCollectionPeer::TABLE_NAME, CollectorCollectionPeer::CONTENT_CATEGORY_ID, ContentCategoryPeer::ID
      ));
  }

  public function hasCollectionsWithCollectibles()
  {
    return $this
      ->where(sprintf(
        'EXISTS (SELECT 1 FROM %s WHERE %s = %s AND %s <> 0)',
        CollectorCollectionPeer::TABLE_NAME, ContentCategoryPeer::ID, CollectorCollectionPeer::NUM_ITEMS
      ));
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
   * Filter by descendants of our root
   *
   * @return    ContentCategoryQuery
   */
  public function descendantsOfRoot()
  {
    return $this
      ->descendantsOf(ContentCategoryQuery::create()->findRoot());
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

  /**
   * Order by the collections count (combined number)
   *
   * @param string $order
   * @return ContentCategoryQuery
   */
  public function orderByNumCollections($order = Criteria::ASC)
  {
    $this->addSelfSelectColumns();
    $this->addAsColumn(
      'num_collections',
        sprintf(
          '(%s + %s)',
          sprintf(
            '(SELECT COUNT(*) FROM %s WHERE %s=%s)',
            CollectorCollectionPeer::TABLE_NAME,
            CollectorCollectionPeer::CONTENT_CATEGORY_ID,
            ContentCategoryPeer::ID
          ),
          sprintf(
            '(SELECT COUNT(*) FROM %s WHERE %s IN (%s))',
            CollectorCollectionPeer::TABLE_NAME,
            CollectorCollectionPeer::CONTENT_CATEGORY_ID,
            sprintf(
              'SELECT %s AS "Id" FROM %s AS r WHERE (%s>%s AND %s<%s)',
              ContentCategoryPeer::alias('r', ContentCategoryPeer::ID),
              ContentCategoryPeer::TABLE_NAME,
              ContentCategoryPeer::alias('r', ContentCategoryPeer::LEFT_COL),
              ContentCategoryPeer::LEFT_COL,
              ContentCategoryPeer::alias('r', ContentCategoryPeer::LEFT_COL),
              ContentCategoryPeer::RIGHT_COL
            )
          )
        )

    );

    switch ($order) {
      case 'asc':
        $this->addAscendingOrderByColumn('num_collections');
        break;
      case 'desc':
        $this->addDescendingOrderByColumn('num_collections');
        break;
    }

    return $this;
  }
}
