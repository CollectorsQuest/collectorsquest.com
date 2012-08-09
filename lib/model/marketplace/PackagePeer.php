<?php

require 'lib/model/marketplace/om/BasePackagePeer.php';

class PackagePeer extends BasePackagePeer
{

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
          number_format($package->getPackagePrice(), 2),
          0 < $discountedPrice ? number_format($discountedPrice, 2) : 'Free');
      }
      else
      {
        $price = number_format($package->getPackagePrice(), 2);
      }

      $packages[$package->getId()] = sprintf('%s ‒ %s', $price, $package->getPackageName());
    }

    return $packages;
  }
}
