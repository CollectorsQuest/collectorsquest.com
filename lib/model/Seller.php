<?php

class Seller extends Collector
{

  /**
   * Check if current user has credits left
   *
   * @return bool
   */
  public function hasPackageCredits()
  {
    return 0 < $this->getCreditsLeft();
  }

  /**
   * Retrieve total number of credits for active packages for the current user
   *
   * @return int
   *
   * @todo unit tests
   */
  public function getPackageCreditsSum()
  {
    $q = PackageTransactionQuery::create()
        ->filterByCollector($this)
        ->filterByPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PAID)
        ->filterByExpiryDate(time(), Criteria::GREATER_EQUAL)
        ->clearSelectColumns()
        ->addAsColumn('total', 'SUM(credits)');

    return (int)PackageTransactionPeer::doSelectStmt($q)->fetchColumn(0);
  }

  /**
   * Retrieve number of seller credits left for use
   *
   * @return int
   *
   * @todo unit tests
   */
  public function getCreditsLeft()
  {
    $packages = PackageTransactionQuery::create()
        ->filterByCollector($this)
        ->filterByPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PAID)
        ->notExpired()
        ->find()
        ->toKeyValue('PrimaryKey', 'Credits');
        ;

    $totalCredits = array_sum($packages);

    $creditsUsed = PackageTransactionCreditQuery::create()
        ->filterByPackageTransactionId(array_keys($packages))
        ->count();

    return $totalCredits - $creditsUsed;
  }

}
