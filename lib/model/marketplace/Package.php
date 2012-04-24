<?php

require 'lib/model/marketplace/om/BasePackage.php';

class Package extends BasePackage
{
  private $discount = null;
  private $discountType = null;

	public function __toString()
	{
		return $this->getPackageName();
	}

	public static function packageName($snId)
	{
		$oCriteria =new Criteria();
		$oCriteria->add(PackagePeer::ID,$snId);
		$omCollector = PackagePeer::doSelectOne($oCriteria);

		return $omCollector->getPackageName();
	}

  public function applyPromo(Promotion $promotion)
  {
    if (PromotionPeer::DISCOUNT_FIXED == $promotion->getAmountType())
    {
      $this->discount = $promotion->getAmount();
    }
    else
    {
      $this->discount = (float)($this->getPackagePrice() * $promotion->getAmount()) / 100;
    }
    $this->discountType = $promotion->getAmountType();
  }

  public function getDiscount()
  {
    return $this->discount;
  }

  public function getDiscountType()
  {
    return $this->discountType;
  }

}
