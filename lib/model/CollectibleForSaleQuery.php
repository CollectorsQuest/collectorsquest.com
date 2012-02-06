<?php

/**
 * Skeleton subclass for performing query and update operations on the 'collectible_for_sale' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class CollectibleForSaleQuery extends BaseCollectibleForSaleQuery
{
  public function filterBySeller($seller = null)
  {
    if (!is_null($seller))
    {
      return $this->useCollectibleQuery()
        ->filterByCollectorId($seller)
        ->enduse()
      ;
    }
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

// CollectibleForSaleQuery
