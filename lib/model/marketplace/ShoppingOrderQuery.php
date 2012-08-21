<?php

require 'lib/model/marketplace/om/BaseShoppingOrderQuery.php';

class ShoppingOrderQuery extends BaseShoppingOrderQuery
{

  /**
   * @return ShoppingOrderQuery
   */
  public function search($q)
  {
    return $this;
  }

  /**
   * @return ShoppingOrderQuery
   */
  public function paid()
  {
    return $this
      ->rightJoinShoppingPaymentRelatedByShoppingPaymentId()
      ->useShoppingPaymentRelatedByShoppingPaymentIdQuery()
        ->filterByStatus(ShoppingPaymentPeer::STATUS_COMPLETED)
      ->endUse()
      ->groupBy(ShoppingOrderPeer::ID);
  }

}
