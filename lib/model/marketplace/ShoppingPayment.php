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
      return 'SP-'. $this->getId();
    }

    return null;
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
