<?php

require 'lib/model/legacy/om/BaseCollectionCategoryQuery.php';

class CollectionCategoryQuery extends BaseCollectionCategoryQuery
{
  /**
   * @param string | CollectionCategory $category
   * @param string $criteria
   *
   * @return CollectionCategoryQuery
   */
  public function filterByParent($category = null, $criteria = Criteria::EQUAL)
  {
    if (is_numeric($category))
    {
      $category = CollectionCategoryQuery::create()->findOneById($category);
    }

    $parent_id = ($category instanceof CollectionCategory) ? $category->getId() : 0;
    $this->filterByParentId($parent_id, $criteria);

    return $this;
  }

	public function isParent()
  {
    $this->filterByParentId(0, Criteria::EQUAL);

    return $this;
  }

  public function isNotParent()
  {
    $this->filterByParentId(0, Criteria::GREATER_THAN);

    return $this;
  }

  public function filterByParentName($name = null)
  {
    if (is_null($name))
    {
      return $this;
    }

    $this->useQuery('CollectionCategory')
      ->filterByName("%$q%", Criteria::LIKE)
      ->endUse();

    return $this;
  }

  /**
   * Use the CollectionCategoryRelatedByParentId relation CollectionCategory object
   *
   * @see       useQuery()
   *
   * @param     string $relationAlias optional alias for the relation,
   *                                   to be used as main alias in the secondary query
   * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
   *
   * @return    CollectionCategoryQuery A secondary query class using the current class as primary query
   */
  public function useCollectionCategoryRelatedByParentIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
  {
    return $this
      ->joinCollectionCategoryRelatedByParentId($relationAlias, $joinType)
      ->useQuery($relationAlias ? $relationAlias : 'CollectionCategoryRelatedByParentId', 'CollectionCategoryQuery');
  }

  /**
   * Adds a JOIN clause to the query using the CollectionCategoryRelatedByParentId relation
   *
   * @param     string $relationAlias optional alias for the relation
   * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
   *
   * @return    CollectionCategoryQuery The current query, for fluid interface
   */
  public function joinCollectionCategoryRelatedByParentId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
  {
    $tableMap = $this->getTableMap();
    $relationMap = $tableMap->getRelation('CollectionCategoryRelatedByParentId');

    // create a ModelJoin object for this join
    $join = new ModelJoin();
    $join->setJoinType($joinType);
    $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
    if ($previousJoin = $this->getPreviousJoin())
    {
      $join->setPreviousJoin($previousJoin);
    }

    // add the ModelJoin to the current object
    if($relationAlias)
    {
      $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
      $this->addJoinObject($join, $relationAlias);
    }
    else
    {
      $this->addJoinObject($join, 'CollectionCategoryRelatedByParentId');
    }

    return $this;
  }

  /**
   * Filter the query by a related CollectionCategory object
   *
   * @param     CollectionCategory|PropelCollection $collectionCategory The related object(s) to use as filter
   * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
   *
   * @return    CollectionCategoryQuery The current query, for fluid interface
   */
  public function filterByCollectionCategoryRelatedByParentId($collectionCategory, $comparison = null)
  {
    if ($collectionCategory instanceof CollectionCategory)
    {
      return $this
        ->addUsingAlias(CollectionCategoryPeer::PARENT_ID, $collectionCategory->getId(), $comparison);
    }
    elseif ($collectionCategory instanceof PropelCollection)
    {
      if (null === $comparison)
      {
        $comparison = Criteria::IN;
      }
      return $this
        ->addUsingAlias(CollectionCategoryPeer::PARENT_ID, $collectionCategory->toKeyValue('PrimaryKey', 'Id'), $comparison);
    }
    else
    {
      throw new PropelException('filterByCollectionCategoryRelatedByParentId() only accepts arguments of type CollectionCategory or PropelCollection');
    }
  }

}
