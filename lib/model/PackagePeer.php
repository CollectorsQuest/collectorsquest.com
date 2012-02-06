<?php


/**
 * Skeleton subclass for performing query and update operations on the 'package' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class PackagePeer extends BasePackagePeer
{
	/* added by Prakash Panchal 29-3-2011
	* getCollectionAsPerCollector function.
	* return object
	*/
	public static function getAllPackages($snIdPackage='')
	{
		$oCriteria = new Criteria();
    $oCriteria->add(PackagePeer::ID, 9999, Criteria::LESS_THAN);
		$oCriteria->addAscendingOrderByColumn(PackagePeer::PLAN_TYPE);

		return PackagePeer::doSelectStmt($oCriteria);
	}

} // PackagePeer
