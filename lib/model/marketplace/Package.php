<?php

class Package extends BasePackage
{

  protected $discount = null;
  protected $discountType = null;

  /**
   * @param     Promotion $promotion
   * @return    Package
   */
  public function applyPromo(Promotion $promotion)
  {
    if (PromotionPeer::DISCOUNT_FIXED == $promotion->getAmountType())
    {
      $this->discount = $promotion->getAmount();
    }
    else
    {
      $this->discount = (float) ($this->getPackagePrice() * $promotion->getAmount()) / 100;
    }

    $this->discountType = $promotion->getAmountType();

    return $this;
  }

  /**
   * Get the calculated discount
   *
   * @return    float
   */
  public function getDiscount()
  {
    return $this->discount;
  }

  /**
   * @return    string PromotionPeer::DISCOUNT_FIXED|PromotionPeer::DISCOUNT_PERCENT
   */
  public function getDiscountType()
  {
    return $this->discountType;
  }

  /**
   * @param     Promotion $promotion
   * @return    float 0 or more
   */
  public function getPriceWithDiscount(Promotion $promotion = null)
  {
    if (null === $promotion)
    {
      return $this->getPackagePrice();
    }
    else
    {
      $this->applyPromo($promotion);

      return (float) max(0, (float) $this->getPackagePrice() - $this->getDiscount());
    }
  }

}
