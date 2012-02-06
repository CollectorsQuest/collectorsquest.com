<?php


/**
 * Skeleton subclass for representing a row from the 'package_transaction' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class PackageTransaction extends BasePackageTransaction
{
	/* added by Prakash Panchal 13-APR-2011
	 * savePackageTransaction function.
	 * return object
	 */
	public static function savePackageTransaction($amData = array())
	{
		$oPackageTransaction = new PackageTransaction();
		$oPackageTransaction->setCollectorId($amData['collector_id']);
		$oPackageTransaction->setPackageId($amData['package_id']);
		$oPackageTransaction->setMaxItemsForSale($amData['max_items_for_sale']);
		$oPackageTransaction->setPackagePrice($amData['package_price']);
		$oPackageTransaction->setExpiryDate(date('Y-m-d h:i:s', strtotime('+365 days')));
		$oPackageTransaction->setPaymentStatus($amData['payment_status']);

		try
		{
			$oPackageTransaction->save();
		}
		catch (PropelException $e)
		{
			return null;
		}

		return $oPackageTransaction;
	}
	/* added by Prakash Panchal 14-APR-2011
	 * updatePaymentStatus function.
	 * return object
	 */
	public static function updatePaymentStatus($amData = array())
	{
		$oPackageTransaction = PackageTransactionPeer::retrieveByPK($amData['id']);
		$oPackageTransaction->setPackagePrice($amData['package_price']);
		$oPackageTransaction->setPaymentStatus($amData['payment_status']);
		try
		{
			$oPackageTransaction->save();
		}
		catch (PropelException $e)
		{
			return null;
		}

		return $oPackageTransaction;
	}
} // PackageTransaction
