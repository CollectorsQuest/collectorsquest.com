<?php
include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../../lib/validator/shipping-rates/shippingRateAmountInCentsOrPercentValidatorSchema.class.php');

$t = new lime_test(7, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing /lib/validator/shipping-rates/shippingRateAmountInCentsOrPercentValidatorSchema.class.php');

$tests = array(
//array($valid, $values, $test_message)
  array(false,  array('amount_in_cents' =>  0, 'amount_in_percent' =>  0, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE), 'either amount in cents or amount in percent has to be set'),
  array(true,   array('amount_in_cents' => 10, 'amount_in_percent' =>  0, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE), 'amount in cents set is validated'),
  array(true,   array('amount_in_cents' =>  0, 'amount_in_percent' => 10, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE), 'amount in percent set is validated'),
  array(false,  array('amount_in_cents' => 10, 'amount_in_percent' => 10, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_FLAT_RATE), 'only one of the two values must be set'),
  array(false,  array('amount_in_cents' => 10, 'amount_in_percent' =>  0, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING), 'when CALCULATION_TYPE_NO_SHIPPING is set having either amount set is an error'),
  array(false,  array('amount_in_cents' =>  0, 'amount_in_percent' => 10, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING), 'when CALCULATION_TYPE_NO_SHIPPING is set having either amount set is an error'),
  array(true,   array('amount_in_cents' =>  0, 'amount_in_percent' =>  0, 'calculation_type' => ShippingRatePeer::CALCULATION_TYPE_NO_SHIPPING), 'CALCULATION_TYPE_NO_SHIPPING is set amount set to 0 is validated'),
);

$v = new shippingRateAmountInCentsOrPercentValidatorSchema();


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

