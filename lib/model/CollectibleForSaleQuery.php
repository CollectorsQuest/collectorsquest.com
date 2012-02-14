<?php

require 'lib/model/om/BaseCollectibleForSaleQuery.php';

class CollectibleForSaleQuery extends BaseCollectibleForSaleQuery
{
  public function filterBySeller($seller = null)
  {
    if (!is_null($seller))
    {
      $this->useCollectibleQuery()
        ->filterByCollectorId($seller)
        ->enduse()
      ;
    }

    return $this;
  }

  public function filterByOffersCount($hasOffers = null)
  {
    if (!is_null($hasOffers) and (bool)$hasOffers)
    {
      return $this->useCollectibleOfferQuery()
        ->filterByStatus(array('pending', 'counter'), Criteria::IN)
        ->groupByCollectibleForSaleId()
        ->endUse();
    }

    return $this;
  }

}
