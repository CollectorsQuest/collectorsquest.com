<?php

class Seller extends Collector
{

  public function getBusinessName()
  {
    return $this->getSellerSettingsPaypalBusinessName();
  }

  public function getFullName()
  {
    return implode(' ', array(
      $this->getSellerSettingsPaypalFirstName(),
      $this->getSellerSettingsPaypalLastName()
    ));
  }

  public function getPayPalEmail()
  {
    return $this->getSellerSettingsPaypalEmail();
  }

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
    return (integer) PackageTransactionQuery::create()
      ->filterByCollector($this)
      ->paidFor()
      ->notExpired()
      ->withColumn('SUM(PackageTransaction.Credits)', 'CreditsTotal')
      ->select('CreditsTotal')
      ->findOne();
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
      ->paidFor()
      ->notExpired()
      ->withColumn('SUM(PackageTransaction.Credits) - SUM(PackageTransaction.CreditsUsed)', 'CreditsLeft')
      ->select('CreditsLeft')
      ->findOne();
  }

}
