<?php

require 'lib/model/om/BasePackagePeer.php';

class PackagePeer extends BasePackagePeer
{
	public static function getAllPackages()
	{
		$oCriteria = new Criteria();
    $oCriteria->add(PackagePeer::ID, 9999, Criteria::LESS_THAN);
		$oCriteria->addAscendingOrderByColumn(PackagePeer::PLAN_TYPE);

		return PackagePeer::doSelectStmt($oCriteria);
	}
}
