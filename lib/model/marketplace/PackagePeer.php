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
   * @return Package[]
   */
  public static function getAllPackagesForSelectGroupedByPlanType()
  {
    /* @var $results Package[] */
    $results = PackageQuery::create()
      ->filterById(9999, Criteria::LESS_THAN)
      ->find();

    $packages = array();
    foreach ($results as $package)
    {
      $packages[$package->getPlanType()][$package->getId()] = sprintf('%s - %s', money_format('%.2n', $package->getPackagePrice()), $package->getPackageName());
    }

    return $packages;
  }
}
