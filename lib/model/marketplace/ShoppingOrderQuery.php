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

  /**
   * Collector Buyer filter
   * @param $value
   * @return ShoppingOrderQuery
   */
  public function filterByCollectorBuyer($value)
  {
    $this->addAlias('buyer_col', CollectorPeer::TABLE_NAME);
    $this->addJoin(
      ShoppingOrderPeer::COLLECTOR_ID, CollectorPeer::alias('buyer_col', CollectorPeer::ID), Criteria::LEFT_JOIN
    );
    $this->add(CollectorPeer::alias('buyer_col', CollectorPeer::DISPLAY_NAME), '%' . $value . '%', Criteria::LIKE);
    return $this;
  }

  /**
   * Collector Seller filter
   * @param $value
   * @return ShoppingOrderQuery
   */
  public function filterByCollectorSeller($value)
  {
    $this->addAlias('seller_col', CollectorPeer::TABLE_NAME);
    $this->addJoin(
      ShoppingOrderPeer::SELLER_ID, CollectorPeer::alias('seller_col', CollectorPeer::ID), Criteria::LEFT_JOIN
    );
    $this->add(
      CollectorPeer::alias('seller_col', CollectorPeer::DISPLAY_NAME), '%' . $value . '%', Criteria::LIKE
    );
    return $this;
  }

  /**
   * Payment Status Filter
   * @param $value
   * @return ShoppingOrderQuery
   */
  public function filterByPaymentStatus($value)
  {
    $this->addAlias('payment_col', CollectorPeer::TABLE_NAME);
    $this->addJoin(
      ShoppingOrderPeer::ID,
      CollectorPeer::alias('payment_col', ShoppingPaymentPeer::SHOPPING_ORDER_ID), Criteria::LEFT_JOIN
    );
    $this->add(
      CollectorPeer::alias('payment_col', ShoppingPaymentPeer::STATUS), $value
    );
    return $this;
  }
}
