<?php

require 'lib/model/marketplace/om/BaseCollectibleForSaleQuery.php';

class CollectibleForSaleQuery extends BaseCollectibleForSaleQuery
{

  /**
   * @return CollectibleForSaleQuery
   */
  public function isForSale()
  {
    return $this
      // ->isPartOfCollection()
      ->filterByIsReady(true)
      ->filterByPriceAmount(1, Criteria::GREATER_EQUAL)
      ->filterByQuantity(1, Criteria::GREATER_EQUAL)
      ->hasActiveCredit();
  }

  /**
   * Filter on collectibles that have an active (not yet expired) transaction credit,
   * ie are paid to be shown as for sale
   *
   * @return    CollectibleForSaleQuery
   */
  public function hasActiveCredit()
  {
    return $this
      ->useCollectibleQuery('collectible_check_credit_alias', Criteria::INNER_JOIN)
        ->joinPackageTransactionCredit(null, Criteria::INNER_JOIN)
        ->addJoinCondition(
          'PackageTransactionCredit',
          'PackageTransactionCredit.EXPIRY_DATE >= NOW()'
        )
      ->endUse();
  }

  /**
   * @return CollectibleForSaleQuery
   */
  public function isPartOfCollection()
  {
    return $this
      ->useCollectibleQuery()
      ->isPartOfCollection()
      ->endUse();
  }

  /**
   * Does not check for a transaction credit present or not!
   *
   * @return CollectibleForSaleQuery
   * @deprecated
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
        ->filterByContentCategoryWithDescendants($content_category, $comparison)
      ->endUse();
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

  /**
   * @param  array   $tags
   * @param  string  $comparison
   *
   * @return CollectibleForSaleQuery
   */
  public function filterByTags($tags, $comparison = Criteria::IN)
  {
    return $this
      ->joinCollectible()
      ->useCollectibleQuery()
        ->filterByTags($tags, $comparison)
        //->orderByTags($tags, $comparison)
      ->endUse();
  }

  /**
   * @param  array   $tags
   * @param  string  $comparison
   *
   * @return CollectibleForSaleQuery
   */
  public function orderByTags($tags, $comparison = Criteria::IN)
  {
    return $this
      ->joinCollectible()
      ->useCollectibleQuery()
        ->orderByTags($tags, $comparison)
      ->endUse();
  }

  /**
   * @see Collectile::filterByMachineTags()
   *
   * @param  array   $tags
   * @param  string  $namespace
   * @param  array   $keys
   * @param  string  $comparison
   *
   * @return CollectibleForSaleQuery
   */
  public function filterByMachineTags($tags, $namespace, $keys = array('all'), $comparison = Criteria::IN)
  {
    return $this
      ->joinCollectible()
      ->useCollectibleQuery()
        ->filterByMachineTags($tags, $namespace, $keys, $comparison)
        ->orderByMachineTags($tags, $namespace, $keys, $comparison)
      ->endUse();
  }

  /**
   * @see Collectile::orderByMachineTags()
   *
   * @param  array   $tags
   * @param  string  $namespace
   * @param  array   $keys
   * @param  string  $comparison
   *
   * @return CollectibleForSaleQuery
   */
  public function orderByMachineTags($tags, $namespace, $keys = array('all'), $comparison = Criteria::IN)
  {
    return $this
      ->joinCollectible()
      ->useCollectibleQuery()
        ->orderByMachineTags($tags, $namespace, $keys, $comparison)
      ->endUse();
  }

  /**
   * @param     string $v
   * @return    CollectionCollectibleQuery
   *
   * @see       CollectibleQuery::search()
   */
  public function search($v)
  {
    return $this
      ->useCollectibleQuery()
        ->search($v)
      ->endUse();
  }

  /**
   * @param     string  $order
   * @return    CollectionCollectibleQuery
   */
  public function orderByAverageRating($order = Criteria::DESC)
  {
    return $this
      ->useCollectibleQuery()
        ->orderByAverageRating($order)
      ->endUse();
  }

}
