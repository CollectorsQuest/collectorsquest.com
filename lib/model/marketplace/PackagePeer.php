<?php

require 'lib/model/marketplace/om/BasePackagePeer.php';

class PackagePeer extends BasePackagePeer
{

  /**
   * @param Promotion $promotion
   * @return Package[]
   */
  public static function getAllPackageLabelsForSelectById(Promotion $promotion = null, $options = array())
  {
    /* @var $results Package[] */
    $results = PackageQuery::create()
      ->filterById(9999, Criteria::LESS_THAN)
      ->find();

    $options = array_merge(array(
        'template' => '%num_listings%'
    ), $options);

    $packages = array();
    foreach ($results as $package)
    {
      if (null !== $promotion)
      {
        $discountedPrice = $package->getPriceWithDiscount($promotion);

        $price = sprintf('<span class="old-price">%s</span> <span class="current-price">%s</span>',
          number_format($package->getPackagePrice(), 2),
          0 < $discountedPrice ? number_format($discountedPrice, 2) : 'Free');
      }
      else
      {
        $price = number_format($package->getPackagePrice(), 2);
      }

      $packages[$package->getId()] = strtr($options['template'], array(
          '%num_listings%' => $package->getCredits() == 1
            ? '1 listing'
            : ($package->getCredits() == 9999
              ? '<span class="red-bold">UNLIMITED</span> listings'
              : $package->getCredits() . ' listings'),
          '%package_id_class%' => 'package' . $package->getId(),
          '%price_per_item%' => '$' . number_format(
            $package->getPriceWithDiscount($promotion) / $package->getCredits(),
            2
          ),
          '%discounted_class%' => isset($promotion)
            ? (isset($options['discount_class']) ? $options['discount_class'] : '')
            : '',
          '%package_price%' => '$' . number_format($package->getPackagePrice(), 2),
          '%package_price_discounted%' => isset($promotion)
            ? '$' . $package->getPriceWithDiscount($promotion)
            : '',
      ));
    }

    return $packages;
  }
}
