<?php
include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../../lib/validator/shipping-rates/SimpleShippingCollectorCollectibleForCountryFormValidatorSchema.class.php');

$t = new lime_test(6, new lime_output_color());
$t->diag('Testing /lib/validator/shipping-rates/SimpleShippingCollectorCollectibleForCountryFormValidatorSchema.class.php');

$flat_ship = ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE;
$tests = array(
//array($valid, $values, $test_message)
    array(false, array('shipping_type' => $flat_ship, 'flat_rate' => 0,     'combined_flat_rate' => 0    ), 'Flat rate of 0 is not allowed'),
    array(false, array('shipping_type' => $flat_ship, 'flat_rate' => -10,   'combined_flat_rate' => 0    ), 'Negative Flat rate is not allowed'),
    array(true,  array('shipping_type' => $flat_ship, 'flat_rate' => 10.31, 'combined_flat_rate' => 0    ), 'Positive flat rate allowed'),
    array(false, array('shipping_type' => $flat_ship, 'flat_rate' => 10.31, 'combined_flat_rate' => 12.00), 'Combined flat rate over the standard flat rate is not allowed'),
    array(true,  array('shipping_type' => $flat_ship, 'flat_rate' => 10.31, 'combined_flat_rate' => 10.00), 'Combined flat rate under the standard flat rate is allowed'),
);

$v = new SimpleShippingCollectorCollectibleForCountryFormValidatorSchema();


foreach ($tests as $test)
{
  list($validity, $value, $message) = $test;

  try
  {
    $v->clean($value);
    $is_valid = true;
  }
  catch (sfValidatorErrorSchema $e)
  {
    $is_valid = false;
  }

  $t->ok($validity == $is_valid, '::clean() ' . $message);
}

$cleaned = $v->clean(array('shipping_type' => $flat_ship, 'flat_rate' => 10, 'combined_flat_rate' => 0));
$t->is($cleaned['combined_flat_rate'], 10,
  'If no combined flat rate is specified, the standard flat rate is copied over');