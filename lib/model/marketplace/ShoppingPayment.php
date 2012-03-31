<?php

require 'lib/model/marketplace/om/BaseShoppingPayment.php';

class ShoppingPayment extends BaseShoppingPayment
{
  public function getTrackingId()
  {
    if (!$this->isNew())
    {
      return 'SP-'. $this->getId();
    }

    return null;
  }
}
