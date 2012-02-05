<?php


/**
 * Skeleton subclass for representing a row from the 'promotion_transaction' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
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
} // PromotionTransaction
