<?php
include_once(dirname(__FILE__).'/../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../lib/validator/cqValidatorUSDtoCents.class.php');

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing /lib/validator/cqValidatorUSDtoCents.class.php');

$tests = array(
//  array($value, $result, $test_message)
    array(0, 0, 'zero is zero'),
    array(10, 1000, '10 usd is 1000 cents'),
    array(156.64, 15664, 'periods are handled'),
    array(156.6455555, 15665, 'long numbers after periods are handled'),
    array('156,64', 15664, 'commas are handled (mostly)'),
);


$v = new cqValidatorUSDtoCents();


foreach ($tests as $test)
{
  list($value, $result, $message) = $test;

  $t->is_deeply($v->clean($value), $result, '::clean() ' . $message);
}

