<?php

require 'lib/model/marketplace/om/BaseShoppingOrderQuery.php';

class ShoppingOrderQuery extends BaseShoppingOrderQuery
{
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
    $this->setDistinct();
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
    $this->setDistinct();
    return $this;
  }
}
