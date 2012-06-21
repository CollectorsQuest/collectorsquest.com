<?php


require 'lib/model/marketplace/om/BasePackageTransactionCreditPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'package_transaction_credit' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.marketplace
 */
class PackageTransactionCreditPeer extends BasePackageTransactionCreditPeer
{

  const STANDARD_EXPIRY_TIME = '+ 6 months';

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
      ->findOne();

    if (!$credit)
    {
      $packageTransaction = PackageTransactionQuery::create()
        ->filterByCollectorId($collectible->getCollectorId())
        ->notExpired()
        ->hasUnusedCredits()
        ->orderByExpiryDate() // those that expire sooner first
        ->findOne();

      if ($packageTransaction)
      {
        $credit = new PackageTransactionCredit();
        $credit->setCollectible($collectible);
        $credit->setPackageTransaction($packageTransaction);
        $credit->setExpiryDate(strtotime(self::STANDARD_EXPIRY_TIME));
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
