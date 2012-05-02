<?php

require 'lib/model/marketplace/om/BaseCollectibleOfferPeer.php';

class CollectibleOfferPeer extends BaseCollectibleOfferPeer
{
  /**
   * Retrieve Collectibles offers by their owner
   *
   * @param Collector $collector
   * @param string|array $status
   * @param Criteria $criteria
   *
   * @return array
   */
  public static function doSelectByCollector($collector, $status = null, Criteria $criteria = null)
  {
    $id = $collector instanceof Collector ? $collector->getId() : $collector;

    if (is_null($criteria))
    {
      $criteria = new Criteria;
    }

    $criteria->setDistinct(); //FIXME: Don't know why this presents here
    $criteria->add(CollectibleOfferPeer::COLLECTOR_ID, $id);

    if (!is_null($status))
    {
      $criteria->add(CollectibleOfferPeer::STATUS, (array) $status, Criteria::IN);
    }

    return self::doSelect($criteria);
  }

  /**
   * @static
   *
   * @param  CollectibleForSale  $collectibleForSale
   * @return Criteria
   */
  public static function getBackendIsSoldCriteria($collectibleForSale)
  {
    $criteria = new Criteria();

    $id = $collectibleForSale instanceof CollectibleForSale ? $collectibleForSale->getCollectibleId() : $collectibleForSale;

    $criteria->add(CollectibleOfferPeer::COLLECTIBLE_ID, $id);
    $criteria->add(CollectibleOfferPeer::STATUS, array('accepted'), Criteria::IN);

    return $criteria;
  }

}
