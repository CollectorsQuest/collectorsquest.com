<?php


require 'lib/model/marketplace/om/BaseShippingRateQuery.php';


/**
 * Skeleton subclass for performing query and update operations on the 'shipping_rate' table.
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.marketplace
 */
class ShippingRateQuery extends BaseShippingRateQuery
{

  /**
   *
   * @param     Collector|Collectible $object
   * @param     string $modelAlias
   * @param     PropelPDO $con
   *
   * @return    ShippingRateQuery
   */
  public static function createStubQueryForRelatedObject($object, $modelAlias = null, $con = null)
  {
    if ($object instanceof Collector)
    {
      return ShippingRateCollectorQuery::create($modelAlias, $con)
        ->filterByCollector($object);
    }

    if ($object instanceof Collectible)
    {
      return ShippingRateCollectibleQuery::create($modelAlias, $con)
        ->filterByCollectible($collectible);
    }
  }

}
