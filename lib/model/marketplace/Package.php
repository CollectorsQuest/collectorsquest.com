<?php

require 'lib/model/marketplace/om/BasePackage.php';

class Package extends BasePackage
{
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
}
