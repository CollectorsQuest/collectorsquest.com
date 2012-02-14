<?php

require 'lib/model/om/BasePackageTransactionPeer.php';

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
}
