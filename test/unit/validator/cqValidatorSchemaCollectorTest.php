<?php
include_once(dirname(__FILE__).'/../../bootstrap/model.php');
require_once(dirname(__FILE__).'/../../../lib/validator/cqValidatorSchemaCollector.class.php');

cqTest::resetTables(array(
  'collector', 'collector_profile',
  'collector_email', 'collector_geocache'
));
cqTest::loadFixtures('01_test_collectors/');

$t = new lime_test(6, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing /lib/validator/cqValidatorSchemaCollector.class.php');

$tests = array(
//  array($validity, $values, $test_message)
    array(true, array(), 'The validator does not throw an error if the required fields are not set'),
    array(false, array(
        'username' => '312j312kljdflgs',
        'password' => '3241134123123',
    ), 'The validator throws an error for unexisting user'),
    array(false, array(
        'username' => 'ivan.tanev',
        'password' => 'wrong-pass',
    ), 'The validator throws an error for wrong password'),
    array(true, array(
        'username' => 'ivan.tanev',
        'password' => 'ivanpass',
    ), 'The validator does no throw an error for right user/pass'),
);


$v = new cqValidatorSchemaCollector();


foreach ($tests as $test)
{
  list($validity, $values, $message) = $test;

  try
  {
    $v->clean($values);
    $is_valid = true;
  }
  catch (sfValidatorErrorSchema $e)
  {
    $is_valid = false;
  }

  $t->ok($is_valid == $validity,  '::clean() ' . $message);
}

$cleaned = $v->clean(array('username' => 'ivan.tanev', 'password' => 'ivanpass'));

$t->isa_ok($cleaned['collector'], 'Collector',
  'The validator adds a `collector` instance to the cleaned values');

$t->is($cleaned['collector']->getUsername(), 'ivan.tanev',
  'The right Collecter is added to the cleaned values');
