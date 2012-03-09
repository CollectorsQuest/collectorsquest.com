<?php


require 'lib/model/marketplace/om/BaseShippingRatePeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'shipping_rate' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.marketplace
 */
class ShippingRatePeer extends BaseShippingRatePeer
{
  /**
   * @var array Some calculation types require an amount to be set, and some do not.
   *            This public static variable holds an array describing it:
   * <code>
   *   array(
   *     'require' => CALCULATION_TYPES[],
   *     'not-require => CALCULATION_TYPES[]
   *   );
   * </code>
   */
  public static $calculation_type_amount = array(
      'required' => array(
          self::CALCULATION_TYPE_FLAT_RATE,
          self::CALCULATION_TYPE_PRICE_RANGE,
      ),
      'not-required' => array(
          self::CALCULATION_TYPE_FREE_SHIPPING,
          self::CALCULATION_TYPE_NO_SHIPPING,
      )
  );

}
