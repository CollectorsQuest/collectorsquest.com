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
   * Get count of uses of this promotion code
   *
   * @return int
   */
  public function getUsedQuantity()
  {
    /* @var $q ShoppingPaymentQuery */
    $q = ShoppingPaymentQuery::create()
      ->filterBySellerPromotionId($this->getId())
      ->filterByStatus(ShoppingPaymentPeer::STATUS_CANCELLED, Criteria::ALT_NOT_EQUAL);

    /* @var $q_a ShoppingPaymentArchiveQuery */
    $q_a = ShoppingPaymentArchiveQuery::create()
      ->filterBySellerPromotionId($this->getId())
      ->filterByStatus(ShoppingPaymentArchivePeer::STATUS_CANCELLED, Criteria::ALT_NOT_EQUAL);

    return $q->count() + $q_a->count();
  }
  /**
   * Get time that left to expiring of this promotion code
   *
   * @return DateInterval
   */
  public function getTimeLeft()
  {
    /* @var $dt DateTime */
    $dt = $this->getExpiryDate(null);

    /* @var DateInterval */
    return $dt instanceof DateTime ? $dt->diff(new DateTime('now')) : false;
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
      return false;
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

    // Check date
    /* @var $tl DateInterval|null */
    if ($tl = $this->getTimeLeft())
    {
      if ($tl->invert == 0)
      {
        $this->setIsExpired(true)->save();

        return false;
      }
    }

    // Check quantity
    if ($this->getQuantity() != 0)
    {
      if ($this->getQuantity() <= $this->getUsedQuantity())
      {
        $this->setIsExpired(true)->save();

        return false;
      }
    }

    return true;
  }
}
