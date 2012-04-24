<?php

require 'lib/model/marketplace/om/BasePromotion.php';

class Promotion extends BasePromotion
{

  public function __toString()
  {
    return $this->getPromotionName();
  }

  public static function promotionName($snId)
  {
    $oCriteria = new Criteria();
    $oCriteria->add(PromotionPeer::ID, $snId);
    $omPromotion = PromotionPeer::doSelectOne($oCriteria);

    return $omPromotion->getPromotionName();
  }

  public static function deductPromoCodeUsed($snPromotionId)
  {
    $omPromotion = PromotionPeer::retrieveByPK($snPromotionId);
    $omPromotion->setNoOfTimeUsed($omPromotion->getNoOfTimeUsed() - 1);

    try
    {
      $omPromotion->save();
    }
    catch (PropelException $e)
    {
      return false;
    }
    return $omPromotion;
  }
} // Promotion
