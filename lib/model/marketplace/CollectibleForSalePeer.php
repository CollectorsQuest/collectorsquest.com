<?php

require 'lib/model/marketplace/om/BaseCollectibleForSalePeer.php';

class CollectibleForSalePeer extends BaseCollectibleForSalePeer
{
  public static $conditions = array(
    '' => 'Any', 'excellent' => 'Excellent', 'very good' => 'Very Good',
    'good' => 'Good', 'fair' => 'Fair', 'poor' => 'Poor'
  );

  public static $currencies = array(
    'USD' => 'USD'
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
    $criteria->addDescendingOrderByColumn(CollectibleForSalePeer::COLLECTIBLE_ID);

    if (null !== $active)
    {
      $criteria->add(CollectibleForSalePeer::IS_SOLD, !$active);
    }

    return self::doSelect($criteria);
  }

  /**
   * @param     CollectibleForSale $collectible_for_sale
   * @param     PropelPDO $con
   *
   * @return    boolean
   */
  public static function activate(CollectibleForSale $collectible_for_sale, PropelPDO $con = null)
  {
    if (!$collectible_for_sale->getIsReady() && $collectible_for_sale->hasActiveCredit($con))
    {
      $collectible_for_sale->setIsReady(true);
      $collectible_for_sale->save($con);

      return true;
    }
    else
    {
      // collectible for sale is already active, signal that no change was made
      return false;
    }
  }

  /**
   * @param     CollectibleForSale $collectible_for_sale
   * @param     PropelPDO $con
   *
   * @return    boolean
   */
  public static function deactivate(CollectibleForSale $collectible_for_sale, PropelPDO $con = null)
  {
    if ($collectible_for_sale->getIsReady())
    {
      $collectible_for_sale->setIsReady(false);
      $collectible_for_sale->save($con);

      return true;
    }
    else
    {
      // collectible for sale is already active, signal that no change was made
      return false;
    }
  }

  /**
   * Relist a collectible for sale
   *  - if sold, a new collectible + collectible for sale are cloned from the
   *    originals and a new PackageTransactionCredit is assigned
   *  - if simply expired, just assign a new PackageTransaction credit
   *  - if neither sold nor without a credit, consider the relist a fail (null returned)
   *
   * @param     CollectibleForSale $collectible_for_sale
   * @param     PropelPDO $con
   *
   * @return    CollectibleForSale|null
   */
  public static function relist(CollectibleForSale $collectible_for_sale, PropelPDO $con = null)
  {
    $con  = $con ?: Propel::getConnection();

    // if we are dealing with a sold collectible
    if ($collectible_for_sale->getIsSold())
    {
      // we need to create a full copy of the related collectible object,
      // but it will be kept only if we can give it a PackageTransactionCredit
      $con->beginTransaction();
      try
      {
        // get a custom copy of the related collectible
        $collectible_copy = $collectible_for_sale
          ->getCollectible($con)
          ->customDeepCopy($con);

        // try to create a new PackageTransactionCredit
        PackageTransactionCreditPeer::findActiveOrCreateForCollectible(
          $collectible_copy,
          $con
        );

        // set the collectible for sale to is ready
        $collectible_copy->getCollectibleForSale()
          ->setIsReady(true)
          ->setIsSold(false)
          ->setQuantity(1)
          ->save($con);

        // commit everything to the database
        $con->commit();

        // and return the collectible for sale
        return $collectible_copy->getCollectibleForSale();
      }
      catch (CollectorHasNoCreditsAvailableException $e)
      {
        // there wasn't a credit available for our newly created collectible
        // so we want to remove it from the DB
        $con->rollBack();

        // and signal failure to relist
        return null;
      }
    }
    // else if there is no active PackageTransactionCredit,
    // things are nice and straighforward, no need for object cloning
    elseif (!$collectible_for_sale->hasActiveCredit($con))
    {
      try
      {
        // just try to create a new transaction credit
        PackageTransactionCreditPeer::findActiveOrCreateForCollectible(
          $collectible_for_sale->getCollectible(),
          $con
        );

        $collectible_for_sale
          ->setIsReady(true)
          ->save($con);

        // and return the collectible for sale if successful
        return $collectible_for_sale;
      }
      catch (CollectorHasNoCreditsAvailableException $e)
      {
        // or signal failure if no credits available
        return null;
      }
    }
    // if the collectible for sale DOES currently have an active credit
    else
    {
      // signal failure, because we didn't actually relist anything
      return null;
    }
  }

}
