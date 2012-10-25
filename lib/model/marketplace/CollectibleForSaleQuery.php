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
      ->hasActiveCredit()
      ;
  }

  /**
   * Filter on collectibles that have an active (or inactive) transaction credit,
   * ie are paid (or not paid/expired)
   *
   * @param     boolean $active_credit Whether to filter on collectibles with or
   *                                   without an active credit
   * @return    CollectibleForSaleQuery
   */
  public function hasActiveCredit($active_credit = true)
  {
    if ($active_credit)
    {
      return $this
        ->useCollectibleQuery('collectible_check_credit_alias')
          ->usePackageTransactionCreditQuery()
            ->notExpired()
          ->endUse()
        ->endUse();
    }
    else
    {
      /*
       * In order to select the collectibles for sale without an active credit
       * we first find all that do have one and then add their IDs as a NOT IN
       * condition
       */
      $q = clone $this;
      return $this
        ->filterByCollectibleId(
          $q
            ->useCollectibleQuery('collectible_check_credit_alias')
              ->usePackageTransactionCreditQuery()
                ->notExpired()
              ->endUse()
            ->endUse()
            ->select('CollectibleId')
            ->find()->getArrayCopy(),
          Criteria::NOT_IN
        );
    }
  }

  /**
   * Filter on collectibles that have inactive (expired) transaction credit,
   * ie were paid to be shown as for sale but expired
   *
   * @return    CollectibleForSaleQuery
   */

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
      ->filterByQuantity(1, Criteria::LESS_THAN)
      ->_or()
      ->hasActiveCredit(false);

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

  public function filterByTags($tags, $comparison = null)
  {
    return $this
      ->joinCollectible()
      ->useCollectibleQuery()
        ->filterByTags($tags, $comparison)
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

}
