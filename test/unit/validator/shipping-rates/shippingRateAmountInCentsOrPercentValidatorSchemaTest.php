<?php
include_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../../lib/validator/shipping-rates/shippingRateAmountInCentsOrPercentValidatorSchema.class.php');

$t = new lime_test(null, new lime_output_color());
$t->diag('Testing /lib/validator/shipping-rates/shippingRateAmountInCentsOrPercentValidatorSchema.class.php');

$tests = array(
//array($valid, $values, $test_message)
  array(false,  array('amount_in_cents' => 0,  'amount_in_percent' => 0 ), 'either amount in cents or amount in percent has to be set'),
  array(true,   array('amount_in_cents' => 10, 'amount_in_percent' => 0 ), 'amount in cents set'),
  array(true,   array('amount_in_cents' => 0,  'amount_in_percent' => 10), 'amount in percent set'),
  array(false,  array('amount_in_cents' => 10, 'amount_in_percent' => 10), 'only one of the two values must be set'),
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

