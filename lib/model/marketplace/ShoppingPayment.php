<?php

require 'lib/model/marketplace/om/BaseShoppingPayment.php';

class ShoppingPayment extends BaseShoppingPayment
{
  /**
   * @return null|string
   */
  public function getTrackingId()
  {
    if (!$this->isNew())
    {
      return 'SP-'. $this->getId() .'-'. date('is');
    }

    return null;
  }

  public function setShoppingOrder(ShoppingOrder $shopping_order)
  {
    /**
     * Set the shopping_order_id
     */
    $this->setShoppingOrderId($shopping_order->getId());

    /**
     * Set all the money amounts
     */
    $this->setAmountTotal($shopping_order->getTotalAmount());
    $this->setAmountShippingFee($shopping_order->getShippingFeeAmount());
    $this->setAmountCollectibles($shopping_order->getCollectiblesAmount());
    $this->setAmountTax(0);
  }

  /**
   * @param  array  $request
   * @return void
   */
  public function setPayPalPayRequest($request)
  {
    foreach ((array) $request as $key => $values)
    {
      $key = sfInflector::underscore($key);
      $this->setProperty('paypal.'. $key, serialize($values));
    }
  }
}
