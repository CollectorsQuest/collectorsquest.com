<?php

require 'lib/model/marketplace/om/BasePackagePeer.php';

class PackagePeer extends BasePackagePeer
{

  public static function getAllPackages()
  {
    $oCriteria = new Criteria();
    $oCriteria->add(PackagePeer::ID, 9999, Criteria::LESS_THAN);
    $oCriteria->addAscendingOrderByColumn(PackagePeer::PLAN_TYPE);

    return PackagePeer::doSelectStmt($oCriteria);
  }

  /**
   * @static
   * @return array
   */
  public static function doSelectAllGrouppedByPlanType()
  {
    $packages = array();
    $results = PackageQuery::create()
        ->filterById(9999, Criteria::LESS_THAN)
        ->orderByPlanType()
        ->find();

    foreach ($results as $package)
    {
      $packages[$package->getPlanType()][$package->getId()] = $package;
    }

    return $packages;
  }
}
