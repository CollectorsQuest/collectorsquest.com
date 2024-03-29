<?php

require 'lib/model/marketplace/om/BaseShippingRate.php';

class ShippingRate extends BaseShippingRate
{

  /**
   * Return the float rate, converted from cents to USD
   *
   * @return    float
   */
  public function getFlatRateInUSD()
  {
    return bcdiv($this->getFlatRateInCents(), 100, 2);
  }

  /**
   * Save the flat rate, converting it from USD to cents for storage
   *
   * @param     float $v
   * @return    ShippingRate
   */
  public function setFlatRateInUSD($v)
  {
    return $this->setFlatRateInCents(bcmul(cqStatic::floatval($v, 2), 100));
  }

}
