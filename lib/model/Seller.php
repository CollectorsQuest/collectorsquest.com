<?php

class Seller extends Collector
{

  public function hasPackageCredits()
  {
    return 0 < $this->getCreditsLeft();
  }

  /**
   * @return int
   * @todo unit tests
   */
  public function getTotalPackageCredits()
  {
    $q = PackageTransactionQuery::create()
        ->filterByCollector($this)
        ->filterByPaymentStatus(PackageTransactionPeer::STATUS_PAID)
        ->filterByExpiryDate(time(), Criteria::GREATER_EQUAL)
        ->clearSelectColumns()
        ->addAsColumn('total', 'SUM(credits)');

    return (int)PackageTransactionPeer::doSelectStmt($q)->fetchColumn(0);
  }

  public function getCreditsLeft()
  {
    $totalCredits = $this->getTotalPackageCredits();

    $creditsUsed = PackageTransactionCreditQuery::create()
        ->filterByCollectorId($this->getId())
        ->filterByExpiryDate(time(), Criteria::GREATER_EQUAL)
        ->count();

    return $totalCredits - $creditsUsed;
  }
}
