<?php
include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../../lib/validator/shipping-rates/shippingRatePriceRangeValidatorSchema.class.php');

$t = new lime_test(null, new lime_output_color());
$t->diag('Testing /lib/validator/shipping-rates/shippingRatePriceRangeValidatorSchema.class.php');

$tests = array(
//array($valid, $values, $test_message)
  array(false,  array('price_range_min' => 10, 'price_range_max' => 5 ), 'min bigger than max is invalid'),
  array(true,   array('price_range_min' => 10, 'price_range_max' => 15), 'min smaller than max is valid'),
  array(true,   array('price_range_min' => 10, 'price_range_max' => 0 ), 'min bigger than max is valid, if max == 0'),
  array(true,   array('price_range_min' => 0, 'price_range_max' => 0 ), 'price range 0-0 (flat rate)'),
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

