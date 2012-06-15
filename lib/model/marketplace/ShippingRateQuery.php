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
   * Find the shipping rates and group them by country:
   *
   * <code>
   * $result = array(
   *   'bg' => PropelObjectCollection(ShippingRate[])
   *   'us' => PropelObjectCollection(ShippingRate[]),
   * );
   * </code>
   *
   * @param     PropelPDO $con
   * @return    array
   */
  public function findAndGroupByCountryCode(PropelPDO $con = null)
  {
    /* @var $object ShippingRate */

    $objects = $this->find($con);
    $results = array();

    foreach ($objects as $object)
    {
      $country_code = $object->getCountryIso3166();

      if (!isset($results[$country_code]))
      {
        $collection = new PropelObjectCollection();
        $collection->setModel(get_class($object));
        $results[$country_code] = $collection;
      }

      $results[$country_code][] = $object;
    }

    return $results;
  }

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
        ->filterByCollectible($object);
    }
  }

}
