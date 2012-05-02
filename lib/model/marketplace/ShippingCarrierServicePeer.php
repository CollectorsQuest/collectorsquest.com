<?php


/**
 * Skeleton subclass for performing query and update operations on the 'shipping_carrier_service' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.marketplace
 */
class ShippingCarrierServicePeer extends BaseShippingCarrierServicePeer
{

  /**
   * @var array Some calculation types require an amount to be set, and some do not.
   *            This public static variable holds an array describing it:
   * <code>
   *   array(
   *     'require' => CALCULATION_TYPE_VALUE[],
   *     'not-require => CALCULATION_TYPE_VALUE[]
   *   );
   * </code>
   */
  public static $calculation_type_amount = array(
      'required' => array(
          ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE,
      ),
      'not-required' => array(
          ShippingRatePeer::CALCULATION_TYPE_FREE_SHIPPING,
          ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING,
          ShippingRatePeer::CALCULATION_TYPE_LOCAL_PICKUP,
      )
  );

}
