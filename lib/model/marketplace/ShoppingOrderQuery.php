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
  public function isPaid()
  {
    return $this
      ->rightJoinShoppingPaymentRelatedByShoppingPaymentId()
      ->useShoppingPaymentRelatedByShoppingPaymentIdQuery()
        ->filterByStatus(ShoppingPaymentPeer::STATUS_COMPLETED)
      ->endUse()
      ->groupBy(ShoppingOrderPeer::ID);
  }

  /**
   * @return ShoppingOrderQuery
   */
  public function isPaidOrConfirmed()
  {
    return $this
      ->rightJoinShoppingPaymentRelatedByShoppingPaymentId()
      ->useShoppingPaymentRelatedByShoppingPaymentIdQuery()
        ->filterByStatus(ShoppingPaymentPeer::STATUS_COMPLETED)
        ->_or()
        ->filterByStatus(ShoppingPaymentPeer::STATUS_CONFIRMED)
      ->endUse()
      ->groupBy(ShoppingOrderPeer::ID);
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
    $this->setDistinct();
    return $this;
  }

  /**
   * Payment Status Filter
   *
   * @param     $value
   * @return    ShoppingOrderQuery
   */
  public function filterByPaymentStatus($value)
  {
    return $this
      ->useShoppingPaymentRelatedByShoppingPaymentIdQuery()
        ->filterByStatus($value)
      ->endUse()
      ->setDistinct();
  }

}
