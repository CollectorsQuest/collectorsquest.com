<?php

require 'lib/model/marketplace/om/BasePackageTransactionCreditQuery.php';

class PackageTransactionCreditQuery extends BasePackageTransactionCreditQuery
{

  /**
   * Filter by not expired yet
   *
   * @param     mixed $now The current time
   * @return    PackageTransactionCreditQuery
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
   * @return    PackageTransactionCreditQuery
   */
  public function isExpired($now = null)
  {
    return $this
      ->filterByExpiryDate($now ?: time(), Criteria::LESS_THAN);
  }

}
