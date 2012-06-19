<?php


require 'lib/model/marketplace/om/BaseShippingRate.php';


/**
 * Skeleton subclass for representing a row from the 'shipping_rate' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.marketplace
 */
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
