<?php

require 'lib/model/marketplace/om/BaseShoppingOrderCollectibleQuery.php';

class ShoppingOrderCollectibleQuery extends BaseShoppingOrderCollectibleQuery
{

  public function search($q)
  {
    return $this;
  }


  public function paid()
  {
    return $this
      ->useShoppingOrderQuery()
        ->useShoppingPaymentRelatedByShoppingOrderIdQuery()
          ->filterByStatus(ShoppingPaymentPeer::STATUS_COMPLETED)
        ->endUse()
      ->endUse()
      ->groupBy(ShoppingOrderCollectiblePeer::ID)
      ;
  }

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
