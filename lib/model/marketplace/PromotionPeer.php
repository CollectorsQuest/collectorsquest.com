<?php

require 'lib/model/marketplace/om/BasePromotionPeer.php';

class PromotionPeer extends BasePromotionPeer
{

  const DISCOUNT_FIXED   = 'Fix';
  const DISCOUNT_PERCENT = 'Percentage';

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
