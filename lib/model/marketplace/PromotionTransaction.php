<?php

require 'lib/model/marketplace/om/BasePromotionTransaction.php';

class PromotionTransaction extends BasePromotionTransaction
{
	/* added by Prakash Panchal 21-APR-2011
	 * savePromotionTransaction function.
	 * return object
	 */
	public static function savePromotionTransaction($amData = array())
	{
		$oPromotionTransaction = new PromotionTransaction();
		$oPromotionTransaction->setCollectorId($amData['collector_id']);
		$oPromotionTransaction->setPromotionId($amData['promotion_id']);
		$oPromotionTransaction->setAmount($amData['amount']);
		$oPromotionTransaction->setAmountType($amData['amount_type']);

		try
		{
			$oPromotionTransaction->save();
		}
		catch (PropelException $e)
		{
			return false;
		}

		return $oPromotionTransaction;
	}
}
