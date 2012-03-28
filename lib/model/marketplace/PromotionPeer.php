<?php

require 'lib/model/marketplace/om/BasePromotionPeer.php';

class PromotionPeer extends BasePromotionPeer
{

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
