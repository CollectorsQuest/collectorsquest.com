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

  /**
   * Check is Promotion code valid
   *
   * @param Collector $collector
   * @param Collectible $collectible
   * @return bool
   */
  public function isValid(Collector $collector = null, Collectible $collectible = null)
  {
    if ($this->getIsExpired())
    {
      return true;
    }

    // Check collectible and collector
    if ($this->getCollectibleId())
    {
      if ($this->getCollectibleId() != $collectible->getId())
      {
        return false;
      }
      if ($this->getCollectorId())
      {
        if ($this->getCollectorId() != $collector->getId())
        {
          return false;
        }
      }
      return true;
    }

    // Check quantity
    if ($this->getQuantity() != 0)
    {
      $q = ShoppingPaymentQuery::create()
        ->filterBySellerPromotionId($this->getId());
      if ($this->getQuantity() >= $q->count())
      {
        return false;
      }
    }

    // Check date
    if ($this->getExpiryDate())
    {
      if ($this->getExpiryDate(null) > new DateTime('now'))
      {
        return false;
      }
    }

    return true;
  }
}
