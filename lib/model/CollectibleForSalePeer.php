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
class CollectibleForSalePeer extends BaseCollectibleForSalePeer
{
  public static $conditions = array('' => 'Any', 'excellent' => 'Excellent', 'very good' => 'Very Good', 'good' => 'Good', 'fair' => 'Fair', 'poor' => 'Poor');
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
    $id = $collector instanceof Collector ? $collector->getId() : (int) $collector;

    if (is_null($criteria))
    {
      $criteria = new Criteria;
    }
    $criteria->addJoin(CollectibleForSalePeer::COLLECTIBLE_ID, CollectiblePeer::ID);
    $criteria->addJoin(CollectiblePeer::COLLECTION_ID, CollectionPeer::ID);
    
    if (!is_null($active))
    {
      $criteria->add(CollectibleForSalePeer::IS_SOLD, !$active);
    }
    
    $criteria->add(CollectiblePeer::COLLECTOR_ID, $id);
    $criteria->addDescendingOrderByColumn(CollectibleForSalePeer::ID);

    return self::doSelect($criteria);
  }

}

// CollectibleForSalePeer
