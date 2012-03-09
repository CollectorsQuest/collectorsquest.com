<?php

require 'lib/model/om/BasePromotion.php';

class Promotion extends BasePromotion
{
	// Added By Prakash Panchal. 21-Apr-2011
	/**
	* Initializes internal state of CollectionItemForSale object.
	* @see        parent::__construct()
	*/
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}
	/**
	* function setTableDefinition() to set default value 0 to column id_collection
	*/
	public function __toString()
	{
		return $this->getPromotionName();
	}
	public static function promotionName($snId)
	{
		$oCriteria = new Criteria();
		$oCriteria->add(PromotionPeer::ID,$snId);
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
