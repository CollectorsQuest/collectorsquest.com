<?php

require 'lib/model/marketplace/om/BasePackagePeer.php';

class PackagePeer extends BasePackagePeer
{

  public static function getAllPackages()
  {
    return PackageQuery::create()
        ->filterById(9999, Criteria::LESS_THAN)
        ->find();
  }

  /**
   * @static
   *
   * @param Promotion $promotion
   *
   * @return Package[]
   */
  public static function getAllPackagesForSelectGroupedByPlanType($promotion = null)
  {
    /* @var $results Package[] */
    $results = PackageQuery::create()
      ->filterById(9999, Criteria::LESS_THAN)
      ->find();

    $packages = array();
    foreach ($results as $package)
    {
      if (null !== $promotion)
      {
        $package->applyPromo($promotion);
        $discountedPrice = $package->getPackagePrice() - $package->getDiscount();
        if ($discountedPrice < 0)
        {
          $discountedPrice = 0;
        }

        $price = sprintf('<span class="old-price">%s</span> <span class="current-price">%s</span>',
          money_format('%.2n', $package->getPackagePrice()),
          0 < $discountedPrice ? money_format('%.2n', $discountedPrice) : 'Free');
      }
      else
      {
        $price = money_format('%.2n', $package->getPackagePrice());
      }

      $packages[$package->getId()] = sprintf('%s - %s', $price, $package->getPackageName());
    }

    return $packages;
  }
}
