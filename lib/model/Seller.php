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
   * @return integer
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

    return (integer) PackageTransactionPeer::doSelectStmt($q)->fetchColumn(0);
  }

  /**
   * Retrieve number of seller credits left for use
   *
   * @return integer
   */
  public function getCreditsLeft()
  {
    return (int) PackageTransactionQuery::create()
      ->filterByCollector($this)
      ->notExpired()
      ->withColumn('SUM(PackageTransaction.Credits) - SUM(PackageTransaction.CreditsUsed)', 'CreditsLeft')
      ->select('CreditsLeft')
      ->findOne();
  }

}
