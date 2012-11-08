<?php
include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../../lib/validator/shipping-rates/shippingRatePriceRangeValidatorSchema.class.php');

$t = new lime_test(6, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing /lib/validator/shipping-rates/shippingRatePriceRangeValidatorSchema.class.php');

$tests = array(
//array($valid, $values, $test_message)
  array(false,  array('price_range_min' => 10, 'price_range_max' =>  5, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE ), 'min bigger than max is invalid'),
  array(true,   array('price_range_min' => 10, 'price_range_max' => 15, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE ), 'min smaller than max is valid'),
  array(true,   array('price_range_min' => 10, 'price_range_max' =>  0, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE ), 'min bigger than max is valid, if max == 0'),
  array(false,  array('price_range_min' =>  0, 'price_range_max' =>  0, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE ), 'price range 0-0 is error (price range)'),
  array(false,  array('price_range_min' =>  0, 'price_range_max' => 10, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE   ), 'error is thrown when calculation type is not price range and range is set'),
  array(true,   array('price_range_min' =>  0, 'price_range_max' =>  0, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE   ), 'no error is thrown when calculation type is not price range and no range is set'),
);

$v = new shippingRatePriceRangeValidatorSchema();


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

