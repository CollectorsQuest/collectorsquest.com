<?php


require 'lib/model/marketplace/om/BaseSellerPromotion.php';


/**
 * Skeleton subclass for representing a row from the 'seller_promotion' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.marketplace
 */
class SellerPromotion extends BaseSellerPromotion
{
  public function getAmount()
  {
    return (float) bcdiv((string) parent::getAmount(), 1000, 3);
  }

  public function setAmount($v)
  {
    parent::setAmount((int) bcmul($v, 1000, 3));
  }
}
