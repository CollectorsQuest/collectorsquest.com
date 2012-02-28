<?php

require 'lib/model/marketplace/om/BaseCollectibleForSalePeer.php';

class CollectibleForSalePeer extends BaseCollectibleForSalePeer
{
  public static $conditions = array(
    '' => 'Any', 'excellent' => 'Excellent', 'very good' => 'Very Good',
    'good' => 'Good', 'fair' => 'Fair', 'poor' => 'Poor'
  );

  /**
   * Retrieve collectibles by collector
   *
   * @param Collector|int $collector
   * @param bool $active If set retrieve only active|inactive collectibles
   * @param Criteria $criteria
   *
   * @return array
   */
  public static function doSelectByCollector($collector, $active = null, Criteria $criteria = null)
  {
    $collector_id = $collector instanceof Collector ? $collector->getId() : (int) $collector;

    if (null === $criteria)
    {
      $criteria = new Criteria;
    }

    $criteria->addJoin(CollectibleForSalePeer::COLLECTIBLE_ID, CollectiblePeer::ID);
    $criteria->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID);
    $criteria->add(CollectiblePeer::COLLECTOR_ID, $collector_id);
    $criteria->addDescendingOrderByColumn(CollectibleForSalePeer::ID);

    if (null !== $active)
    {
      $criteria->add(CollectibleForSalePeer::IS_SOLD, !$active);
    }

    return self::doSelect($criteria);
  }

}
