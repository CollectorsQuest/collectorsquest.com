<?php
include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../../lib/validator/shipping-rates/shippingRateCollectionPriceRangeValidatorSchema.class.php');

$t = new lime_test(8, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing /lib/validator/shipping-rates/shippingRateCollectionPriceRangeValidatorSchema.class.php');

$tests = array(
//array($valid, $embedded_form_names, $values, $test_message)
  array(false,  array('embedded_1'), array(
      'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE,
      'embedded_1' => array(
          'price_range_min' => 0,
          'price_range_max' => 10,
      )
  ), 'open range'),
  array(false,  array('embedded_1'), array(
      'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE,
      'embedded_1' => array(
          'price_range_min' => 10,
          'price_range_max' => 20,
      )
  ), 'start from non-zero'),
  array(false,  array('embedded_1', 'embedded_2'), array(
      'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE,
      'embedded_1' => array(
          'price_range_min' => 0,
          'price_range_max' => 18,
      ),
      'embedded_2' => array(
          'price_range_min' => 20,
          'price_range_max' => 0,
      ),
  ), 'hole in range'),
  array(false,  array('embedded_1', 'embedded_2', 'embedded_3'), array(
      'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE,
      'embedded_1' => array(
          'price_range_min' => 0,
          'price_range_max' => 10,
      ),
      'embedded_2' => array(
          'price_range_min' => 5,
          'price_range_max' => 15,
      ),
      'embedded_3' => array(
          'price_range_min' => 15,
          'price_range_max' => 0,
      ),
  ), 'intersecting ranges'),
  array(true,  array('embedded_3', 'embedded_2', 'embedded_1'), array(
      'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE,
      'embedded_1' => array(
          'price_range_min' => 0,
          'price_range_max' => 10,
      ),
      'embedded_2' => array(
          'price_range_min' => 10,
          'price_range_max' => 20,
      ),
      'embedded_3' => array(
          'price_range_min' => 20,
          'price_range_max' => 0,
      ),
  ), 'valid range'),
  array(true,  array('embedded_3', 'embedded_2', 'embedded_1'), array(
      'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE,
      'embedded_1' => array(
          'price_range_min' => 0,
          'price_range_max' => 10,
      ),
      'embedded_2' => array(
          'price_range_min' => 11,
          'price_range_max' => 20,
      ),
      'embedded_3' => array(
          'price_range_min' => 21,
          'price_range_max' => 0,
      ),
  ), 'valid range (with next ranges being +1 of the max of previous range)'),
  array(true,  array('embedded_1'), array(
      'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE,
      'embedded_1' => array(
          'price_range_min' => 0,
          'price_range_max' => 0,
      ),
  ), 'form without a range set (other validators handle empty ranges for price_range calculation type)'),
  array(true,  array('embedded_1'), array(
      'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE,
      'embedded_1' => array(
          'price_range_min' => 10,
          'price_range_max' => 10,
      ),
  ), 'validator does not return error for calculation_type different from price_range'),
);

$v = new shippingRateCollectionPriceRangeValidatorSchema(null, array(
    'embedded_form_names' => array(),
));


foreach ($tests as $test)
{
  list($validity, $embedded_form_names, $value, $message) = $test;

  try
  {
    $v->setOption('embedded_form_names', $embedded_form_names);
    $v->clean($value);
    $is_valid = true;
  }
  catch (sfValidatorErrorSchema $e)
  {
    $is_valid = false;
  }

  $t->ok($validity == $is_valid, '::clean() ' . $message);
}

