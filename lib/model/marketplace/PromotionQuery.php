<?php

require 'lib/model/marketplace/om/BasePromotionQuery.php';

class PromotionQuery extends BasePromotionQuery
{

  /**
   * Make sure promotion code is a strict comparison
   *
   * @param string $promotionCode
   * @param string $comparison
   *
   * @return PromotionQuery
   */
  public function filterByPromotionCode($promotionCode = null, $comparison = null)
  {
    return parent::filterByPromotionCode(
        trim($promotionCode),
        $comparison ?: Criteria::EQUAL
    );
  }

}
