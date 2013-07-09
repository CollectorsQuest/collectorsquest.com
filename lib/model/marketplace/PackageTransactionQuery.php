<?php

require 'lib/model/marketplace/om/BasePackageTransactionQuery.php';

class PackageTransactionQuery extends BasePackageTransactionQuery
{

  /**
   * Filter by not expired yet
   *
   * @param     mixed $now The current time
   * @return    PackageTransactionQuery
   */
  public function notExpired($now = null)
  {
    return $this
      ->filterByExpiryDate($now ?: time(), Criteria::GREATER_EQUAL);
  }

  /**
   * Filter by is expired
   *
   * @param     mixed $now The current time
   * @return    PackageTransactionQuery
   */
  public function isExpired($now = null)
  {
    return $this
      ->filterByExpiryDate($now ?: time(), Criteria::LESS_THAN);
  }

  /**
   * Filter by package transaction that have unused credits left
   *
   * @return PackageTransactionQuery
   */
  public function hasUnusedCredits()
  {
    return $this
      ->where('PackageTransaction.Credits > PackageTransaction.CreditsUsed');
  }

  /**
   * Filter by has been paid for
   *
   * @return PackageTransactionQuery
   */
  public function paidFor()
  {
    return $this
      ->filterByPaymentStatus(PackageTransactionPeer::PAYMENT_STATUS_PAID);
  }

  /**
   * @param   string $column_name
   * @return  PackageTransactionQuery
   */
  public function withCreditsLeftColumn($column_name = 'CreditsLeft')
  {
    return $this
      ->withColumn('SUM(PackageTransaction.Credits) - SUM(PackageTransaction.CreditsUsed)', $column_name);
  }

}
