<?php


/**
 * Skeleton subclass for performing query and update operations on the 'package_transaction' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class PackageTransactionPeer extends BasePackageTransactionPeer 
{
	/* added by Prakash Panchal 19-APR-2011
	 * checkExpiryDate function.
	 * return object
	 */
	public static function checkExpiryDate($snColloectorId)
	{
		$oCriteria = new Criteria();
		$oCriteria->add(PackageTransactionPeer::COLLECTOR_ID, $snColloectorId);
		$oCriteria->addDescendingOrderByColumn(PackageTransactionPeer::ID);
		return PackageTransactionPeer::doSelectOne($oCriteria);
	}
} // PackageTransactionPeer
