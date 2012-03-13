<?php

require 'lib/model/marketplace/om/BaseCollectibleForSale.php';

class CollectibleForSale extends BaseCollectibleForSale
{
  /**
   * Proxy method to Collectible::getCollector()
   *
   * @param  null|PropelPDO  $con
   * @return Collector
   */
  public function getCollector(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollector($con);
  }

  public function getCollectorId(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollectorId();
  }

  /**
   * Proxy method to Collectible::getCollection()
   *
   * @param  null|PropelPDO  $con
   * @return Collection|CollectionDropbox
   */
  public function getCollection(PropelPDO $con = null)
  {
    return $this->getCollectible($con)->getCollection($con);
  }

  /**
   * Get the number of offers made for this CollectibleForSale
   *
   * @param  boolean  $activeOnly
   * @return integer
   */
  public function getOffersCount($activeOnly = null)
  {
    $c = new Criteria();

    if (null !== $activeOnly) {
      $c->add(CollectibleOfferPeer::STATUS, 'pending', $activeOnly ? Criteria::EQUAL : Criteria::NOT_EQUAL);
    }

    return count($this->getCollectible()->getCollectibleOffers($c));
  }

  /**
   * @param Collector|integer $buyer
   * @param null $status
   * @param Criteria|null $criteria
   *
   * @return CollectibleOffer
   */
  public function getCollectibleOfferByBuyer($buyer, $status = null, Criteria $criteria = null)
  {
    $id = $buyer instanceof Collector ? $buyer->getId() : $buyer;

    if (null === $criteria)
    {
      $criteria = new Criteria();
    }

    $criteria->add(CollectibleOfferPeer::COLLECTIBLE_ID, $this->getCollectibleId());
    $criteria->add(CollectibleOfferPeer::COLLECTOR_ID, $id);

    if (null !== $status)
    {
      $criteria->add(CollectibleOfferPeer::STATUS, $status);
    }

    return CollectibleOfferPeer::doSelectOne($criteria);
  }

  /**
   * @return integer
   */
  public function getActiveCollectibleOffersCount()
  {
    $criteria = new Criteria();

    $criteria->add(CollectibleOfferPeer::COLLECTIBLE_ID, $this->getCollectibleId());
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
    $criteria = CollectibleOfferPeer::getBackendIsSoldCriteria($this);

    return CollectibleOfferPeer::doSelectOne($criteria);
  }

  public function getBackendIsSold()
  {
    $criteria = CollectibleOfferPeer::getBackendIsSoldCriteria($this);

    return (bool)CollectibleOfferPeer::doCount($criteria);
  }

}
