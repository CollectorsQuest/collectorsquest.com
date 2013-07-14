<?php

class Promotion extends BasePromotion
{

  /**
   * @return    string $|%
   */
  public function getAmountTypeString()
  {
    return PromotionPeer::AMOUNT_TYPE_FIX == $this->getAmountType()
      ? '$'
      : '%';
  }

}
