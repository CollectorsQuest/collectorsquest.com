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
   */
  public function filterByContentCategory($content_category, $comparison = null)
  {
    return $this
      ->useCollectibleQuery()
        ->useCollectionCollectibleQuery()
          ->useCollectionQuery()
            ->filterByContentCategory($content_category, $comparison)
          ->endUse()
        ->endUse()
      ->endUse();
  }

  /**
   * @param ContentCategory $content_category
   * @param string $comparison
   *
   * @return CollectibleForSaleQuery
   */
  public function filterByContentCategoryWithDescendants($content_category, $comparison = null)
  {
    return $this
      ->useCollectibleQuery()
        ->useCollectionCollectibleQuery()
          ->useCollectionQuery()
            ->filterByContentCategory(
              ContentCategoryQuery::create()
                ->descendantsOfObjectIncluded($content_category)->find(),
              $comparison
            )
          ->endUse()
        ->endUse()
      ->endUse();
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
   * @param  \Collector|null $collector
   * @param  null $comparison

   * @return CollectibleForSaleQuery
   */
  public function filterByCollector(Collector $collector = null, $comparison = null)
  {
    return $this
      ->useCollectibleQuery()
      ->filterByCollector($collector, $comparison)
      ->enduse();
  }

  /**
   * @param  \CollectionCollectible|null $collectible
   * @param  null $comparison

   * @return CollectibleForSaleQuery
   */
  public function filterByCollectionCollectible(CollectionCollectible $collectible = null, $comparison = null)
  {
    return $this
      ->useCollectibleQuery()
      ->filterByCollectionCollectible($collectible, $comparison)
      ->enduse();
  }

  /**
   * @param  \Collection|null $collection
   * @param  null $comparison

   * @return CollectibleForSaleQuery
   */
  public function filterByCollection(Collection $collection = null, $comparison = null)
  {
    return $this
      ->useCollectibleQuery()
      ->filterByCollection($collection, $comparison)
      ->enduse();
  }

  public function filterByTags($tags, $comparison = null)
  {
    return $this
      ->joinCollectible()
      ->useCollectibleQuery()
        ->filterByTags($tags, $comparison)
      ->endUse();
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

  /**
   * @param  string $v
   * @return CollectionCollectibleQuery
   */
  public function search($v)
  {
    return $this
      ->useCollectibleQuery()
      ->search($v)
      ->endUse();
  }

}
