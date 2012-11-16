<?php

require 'lib/model/marketplace/om/BasePromotionTransactionQuery.php';

class PromotionTransactionQuery extends BasePromotionTransactionQuery
{
  /**
   * Filter the query by a related Collector username
   *
   * @param null $username
   * @param null $comparison
   * @return ModelCriteria
   */
  public function filterByCollectorUsername($username = null, $comparison = null)
  {
    return $this->useCollectorQuery()
            ->filterByUsername($username, $comparison)
           ->endUse();
  }

  /**
   * Filter the query by a related Collector email
   *
   * @param null $email
   * @param null $comparison
   * @return ModelCriteria
   */
  public function filterByCollectorEmail($email = null, $comparison = null)
  {
    return $this->useCollectorQuery()
      ->filterByEmail($email, $comparison)
      ->endUse();
  }

}
