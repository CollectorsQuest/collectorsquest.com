<?php

class Promotion extends BasePromotion
{

  /**
   * @return    string $|%
   */
  public function getAmountTypeString()
  {
    return PromotionPeer::DISCOUNT_FIXED == $this->getAmountType()
      ? '$'
      : '%';
  }

}
