<?php

require 'lib/model/marketplace/om/BaseCollectibleForSaleQuery.php';

class CollectibleForSaleQuery extends BaseCollectibleForSaleQuery
{

  /**
   * @return CollectibleForSaleQuery
   */
  public function isForSale()
  {
    $this
      ->filterByIsReady(true)
      ->filterByPriceAmount(1, Criteria::GREATER_EQUAL)
      ->filterByQuantity(1, Criteria::GREATER_EQUAL);

    return $this;
  }

  /**
   * @return CollectibleForSaleQuery
   */
  public function isNotForSale()
  {
    $this
      ->filterByIsReady(false)
      ->_or()
      ->filterByPriceAmount(1, Criteria::LESS_THAN)
      ->_or()
      ->filterByQuantity(1, Criteria::LESS_THAN);

    return $this;
  }

  /**
   * @param  array|float  $priceAmount
   * @param  string  $comparison
   *
   * @return CollectibleForSaleQuery
   */
  public function filterByPrice($priceAmount = null, $comparison = null)
  {
    if (is_array($priceAmount))
    {
      if (isset($priceAmount['min']))
      {
        $priceAmount['min'] = (int) bcmul($priceAmount['min'], 100);
      }
      if (isset($priceAmount['max']))
      {
        $priceAmount['max'] = (int) bcmul($priceAmount['max'], 100);
      }
    }
    else
    {
      $priceAmount = (int) bcmul($priceAmount, 100);
    }

    return $this->filterByPriceAmount($priceAmount, $comparison);
  }

  /**
   * @param ContentCategory|PropelCollection $content_category
   * @param string $comparison
   *
   * @return CollectibleForSaleQuery
   * @throws PropelException
   */
  public function filterByContentCategory($content_category, $comparison = null)
  {
    if ($content_category instanceof ContentCategory)
    {
      /** @var $content_category ContentCategory */

      $this
        ->joinCollectible()
        ->useCollectibleQuery()
          ->joinCollectionCollectible()
          ->useCollectionCollectibleQuery()
            ->joinCollection()
            ->useCollectionQuery()
              ->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $content_category->getId(), $comparison)
            ->endUse()
          ->endUse()
        ->endUse();
    }
    elseif ($content_category instanceof PropelCollection)
    {
      /** @var $content_category PropelCollection */

      if (null === $comparison)
      {
        $comparison = Criteria::IN;
      }

      $this
        ->joinCollectible()
        ->useCollectibleQuery()
          ->joinCollectionCollectible()
          ->useCollectionCollectibleQuery()
            ->joinCollection()
            ->useCollectionQuery()
              ->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $content_category->toKeyValue('PrimaryKey', 'Id'), $comparison)
            ->endUse()
          ->endUse()
        ->endUse();
    }
    else
    {
      throw new PropelException('filterByContentCategory() only accepts arguments of type ContentCategory or PropelCollection');
    }

    return $this;
  }

  /**
   * @param ContentCategory $content_category
   * @param string $comparison
   *
   * @return CollectibleForSaleQuery
   * @throws PropelException
   */
  public function filterByContentCategoryWithDescendants($content_category, $comparison = null)
  {
    /** @var $content_category ContentCategory */
    if ($content_category instanceof ContentCategory)
    {
      $q = $this
        ->joinCollectible()
        ->useCollectibleQuery()
          ->joinCollectionCollectible()
          ->useCollectionCollectibleQuery()
            ->joinCollection()
            ->useCollectionQuery();

      if ($comparison === Criteria::NOT_EQUAL || $comparison === Criteria::NOT_IN)
      {
        $q->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $content_category->getId(), Criteria::NOT_EQUAL);
        if ($children = $content_category->getChildren())
        {
          $q->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $children->toKeyValue('PrimaryKey', 'Id'), Criteria::NOT_IN);
        }
      }
      else
      {
        $q->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $content_category->getId(), Criteria::EQUAL);

        if ($descendants = $content_category->getDescendants())
        {
          $q
            ->_or()
            ->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $descendants->toKeyValue('PrimaryKey', 'Id'), Criteria::IN);
        }
      }

      $q
            ->endUse()
          ->endUse()
        ->endUse();
    }
    else
    {
      throw new PropelException('filterByContentCategory() only accepts arguments of type ContentCategory');
    }

    return $this;
  }

  /**
   * @param  integer  $seller
   * @return CollectibleForSaleQuery
   */
  public function filterBySeller($seller = null)
  {
    if (!is_null($seller))
    {
      $this
        ->useCollectibleQuery()
        ->filterByCollectorId($seller)
        ->enduse();
    }

    return $this;
  }

  /**
   * @param  null|boolean  $hasOffers
   * @return CollectibleForSaleQuery
   */
  public function filterByOffersCount($hasOffers = null)
  {
    if (!is_null($hasOffers) and (bool)$hasOffers)
    {
      return $this
        ->useCollectibleOfferQuery()
        ->filterByStatus(array('pending', 'counter'), Criteria::IN)
        ->groupByCollectibleId()
        ->endUse();
    }

    return $this;
  }

  /**
   * Adds a JOIN clause to the query using the CollectibleOffer relation
   *
   * @param     string $relationAlias optional alias for the relation
   * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
   *
   * @return    CollectibleForSaleQuery The current query, for fluid interface
   */
  public function joinCollectibleOffer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
  {
    $tableMap    = $this->getTableMap();
    $tableMap->addRelation('CollectibleOffer', 'CollectibleOffer', RelationMap::ONE_TO_MANY, array('collectible_id'=>'collectible_id'), null, null, 'CollectibleOffers');
    $relationMap = $tableMap->getRelation('CollectibleOffer');

    // create a ModelJoin object for this join
    $join = new ModelJoin();
    $join->setJoinType($joinType);
    $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
    if ($previousJoin = $this->getPreviousJoin())
    {
      $join->setPreviousJoin($previousJoin);
    }

    // add the ModelJoin to the current object
    if ($relationAlias)
    {
      $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
      $this->addJoinObject($join, $relationAlias);
    }
    else
    {
      $this->addJoinObject($join, 'CollectibleOffer');
    }

    return $this;
  }

  /**
   * Use the CollectibleOffer relation CollectibleOffer object
   *
   * @see       useQuery()
   *
   * @param     string $relationAlias optional alias for the relation,
   *                                   to be used as main alias in the secondary query
   * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
   *
   * @return    CollectibleOfferQuery A secondary query class using the current class as primary query
   */
  public function useCollectibleOfferQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
  {
    return $this
      ->joinCollectibleOffer($relationAlias, $joinType)
      ->useQuery($relationAlias ? $relationAlias : 'CollectibleOffer', 'CollectibleOfferQuery');
  }

}
