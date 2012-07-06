<?php

require 'lib/model/marketplace/om/BasePackageTransactionCreditPeer.php';

class PackageTransactionCreditPeer extends BasePackageTransactionCreditPeer
{

  const STANDARD_EXPIRY_TIME = '6 months';

  /**
   * Will return the active credit for a particular collectible, or create a new
   * one if possible. Whether possible or not will depend on the collector having
   * a related PackageTransaction that is not expired and still has unspent credits.
   *
   * If there is no PackageTransactionCredit can be found and the Collector does
   * not have free credits throw a CollectorHasNoCreditsAvailableException
   *
   * @param     Collectible $collectible
   * @param     PropelPDO $con
   *
   * @return    PackageTransactionCredit
   * @throws    CollectorHasNoCreditsAvailableException
   */
  public static function findActiveOrCreateForCollectible(Collectible $collectible, PropelPDO $con = null)
  {
    $credit = PackageTransactionCreditQuery::create()
      ->filterByCollectible($collectible)
      ->notExpired()
      ->findOne($con);

    if (!$credit)
    {
      $packageTransaction = PackageTransactionQuery::create()
        ->filterByCollectorId($collectible->getCollectorId())
        ->notExpired()
        ->hasUnusedCredits()
        ->orderByExpiryDate(Criteria::ASC) // those that expire sooner first
        ->findOne($con);

      if ($packageTransaction)
      {
        $credit = new PackageTransactionCredit();
        $credit->setCollectible($collectible);
        $credit->setPackageTransaction($packageTransaction);
        $credit->setExpiryDate(strtotime('+'.self::STANDARD_EXPIRY_TIME));
        $credit->save($con);
      }
      else
      {
        throw new CollectorHasNoCreditsAvailableException();
      }
    }

    return $credit;
  }

}
