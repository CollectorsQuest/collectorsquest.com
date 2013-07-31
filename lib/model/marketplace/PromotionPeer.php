<?php

require 'lib/model/marketplace/om/BasePromotionPeer.php';

class PromotionPeer extends BasePromotionPeer
{

  const DISCOUNT_FIXED   = self::AMOUNT_TYPE_FIX;
  const DISCOUNT_PERCENT = self::AMOUNT_TYPE_PERCENTAGE;

  /**
   * Check if promo code exists
   *
   * @static
   * @param string $promoCode
   * @return Promotion
   */
  public static function findByPromotionCode($promoCode)
  {
    return PromotionQuery::create()
        ->findOneByPromotionCode($promoCode);
  }

}
