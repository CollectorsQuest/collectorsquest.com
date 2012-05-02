<?php

require 'lib/model/marketplace/om/BaseCollectibleForSaleQuery.php';

class CollectibleForSaleQuery extends BaseCollectibleForSaleQuery
{

  public function filterBySeller($seller = null)
  {
    if (!is_null($seller))
    {
      $this->useCollectibleQuery()
          ->filterByCollectorId($seller)
          ->enduse();
    }

    return $this;
  }

  public function filterByOffersCount($hasOffers = null)
  {
    if (!is_null($hasOffers) and (bool)$hasOffers)
    {
      return $this->useCollectibleOfferQuery()
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
