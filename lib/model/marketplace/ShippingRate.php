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

  /**
   * Return the combined float rate, converted from cents to USD
   *
   * @return    float
   */
  public function getCombinedFlatRateInUSD()
  {
    if (0 != $this->getCombinedFlatRateInCents())
    {
      return bcdiv($this->getCombinedFlatRateInCents(), 100, 2);
    }
    else
    {
      return $this->getFlatRateInUSD();
    }
  }

  /**
   * Save the combined flat rate, converting it from USD to cents for storage
   *
   * @param     float $v
   * @return    ShippingRate
   */
  public function setCombinedFlatRateInUSD($v)
  {
    return $this->setCombinedFlatRateInCents(bcmul(cqStatic::floatval($v, 2), 100));
  }

}
