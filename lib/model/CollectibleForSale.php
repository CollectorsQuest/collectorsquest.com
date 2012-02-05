<?php

/**
 * Skeleton subclass for representing a row from the 'collectible_for_sale' table.
 *
 * @package    propel.generator.lib.model
 */
class CollectibleForSale extends BaseCollectibleForSale
{
  public function getCollector(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollector($con);
  }

  public function getCollection(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollection($con);
  }

  public function getOffersCount($activeOnly = null)
  {
    $c = new Criteria();
    
    if (!is_null($activeOnly)) {
      $c->add(CollectibleOfferPeer::STATUS, 'pending', $activeOnly ? Criteria::EQUAL : Criteria::NOT_EQUAL);
    }

    return count($this->getCollectibleOffers($c));
  }

  public function getCollectibleOfferByBuyer($buyer, $status = null, Criteria $criteria = null)
  {
    $id = $buyer instanceof Collector ? $buyer->getId() : $buyer;

    if (is_null($criteria))
    {
      $criteria = new Criteria();
    }

    $criteria->add(CollectibleOfferPeer::COLLECTIBLE_FOR_SALE_ID, $this->getId());
    $criteria->add(CollectibleOfferPeer::COLLECTOR_ID, $id);

    if (!is_null($status))
    {
      $criteria->add(CollectibleOfferPeer::STATUS, $status);
    }

    return CollectibleOfferPeer::doSelectOne($criteria);
  }

  public function getActiveCollectibleOffersCount()
  {
    $criteria = new Criteria();

    $criteria->add(CollectibleOfferPeer::COLLECTIBLE_FOR_SALE_ID, $this->getId());
    $criteria->add(CollectibleOfferPeer::STATUS, array('pending', 'completed'), Criteria::IN);

    return CollectibleOfferPeer::doCount($criteria);
  }

  /**
   * Retrieve offer which collectible is sold with
   * 
   * @return CollectibleOffer
   */
  public function getSoldOffer()
  {
    $criteria = CollectibleOfferPeer::getBackendIsSoldCriteria($this->getId());
    
    return CollectibleOfferPeer::doSelectOne($criteria);
  }

  public function getBackendIsSold()
  {
    $criteria = CollectibleOfferPeer::getBackendIsSoldCriteria($this->getId());
    
    return (bool)CollectibleOfferPeer::doCount($criteria);
  }

}
