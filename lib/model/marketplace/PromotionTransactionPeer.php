<?php

require 'lib/model/marketplace/om/BasePromotionTransactionPeer.php';

class PromotionTransactionPeer extends BasePromotionTransactionPeer
{

  /**
   * @static
   *
   * @param Collector|int $collector
   * @param Promotion|int $promotion
   * @param $amount
   * @param string $discountType
   * @return PromotionTransaction
   * @throws Exception
   *
   * @todo unit tests
   */
  public static function newTransaction($collector, $promotion, $amount, $discountType = PromotionPeer::DISCOUNT_FIXED)
  {
    $collectorId = $collector instanceof Collector ? $collector->getId() : $collector;

    if (is_int($promotion))
    {
      $promotion = PromotionPeer::retrieveByPK($promotion);
    }

    if ($amount < 0)
    {
      throw new Exception('Promotion amount can not be less than 0');
    }

    $transaction = new PromotionTransaction();
    $transaction->setCollectorId($collectorId);
    $transaction->setPromotion($promotion);
    $transaction->setAmount($amount);
    $transaction->setAmountType($discountType);

    $transaction->save();

    $promotion->setNoOfTimeUsed($promotion->getNoOfTimeUsed() - 1);
    $promotion->save();

    return $transaction;
  }

  /**
   * @static
   * @param Collector|int $collector
   * @param string $code
   * @return PromotionTransaction|null
   */
  public static function findOneByCollectorAndCode($collector, $code)
  {
    $collectorId = $collector instanceof Collector ? $collector->getId() : $collector;

    return PromotionTransactionQuery::create()
        ->filterByCollectorId($collectorId)
        ->joinPromotion()
        ->usePromotionQuery()
        ->filterByPromotionCode($code)
        ->endUse()
        ->findOne();
  }
}
