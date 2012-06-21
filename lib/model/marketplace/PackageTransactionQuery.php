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
}
