<?php
include_once(dirname(__FILE__).'/../../bootstrap/model.php');
require_once(dirname(__FILE__).'/../../../lib/validator/cqValidatorCollectorByName.class.php');

cqTest::resetTables(array(
  'collector', 'collector_profile',
  'collector_email', 'collector_geocache'
));
cqTest::loadFixtures('01_test_collectors/');

$t = new lime_test(8, array('output' => new lime_output_color(), 'error_reporting' => true));
$t->diag('Testing /lib/validator/cqValidatorCollectorByName.class.php');

  $ivan_tanev_id = CollectorQuery::create()
    ->filterByUsername('ivan.tanev')
    ->select('Id')
    ->findOne();

  $tests = array(
  //  array($validity, $value, $expected_return, $test_message)
      array(false, '', null, 'The validator handles normal string validation'),
      array(false, 'sdfafafadfasdfasdfasdfas', null, 'The validator handles normal string validation'),
      array(true, 'ivan.tanev', $ivan_tanev_id, 'The validator returns the expected ID for collector username'),
      array(true, 'Ivan Tanev', $ivan_tanev_id, 'The validator returns the expected ID for collector display name'),
      array(false, 'Ivan Ivanov', null, 'The validator throws exception when user is ambiguous'),
      array(false, 'Ivan', null, 'The validator throws exception when user not found'),
  );


  $v = new cqValidatorCollectorByName(array(
     'max_length' => 20
  ));


  foreach ($tests as $test)
  {
    list($validity, $value, $expected_return, $message) = $test;

    try
    {
      $return = $v->clean($value);
      $is_valid = true;
    }
    catch (sfValidatorError $e)
    {
      $return = false;
      $is_valid = false;
    }

    if (false == $validity)
    {
      $t->ok(!$is_valid, '::clean() ' . $message);
    }
    else
    {
      $t->is($return, $expected_return, '::clean() ' . $message);
    }
  }


  $v = new cqValidatorCollectorByName(array(
      'return_object' => true,
  ));

$t->isa_ok($v->doClean('ivan.tanev'), 'Collector',
  '::clean() The validator can return the actual Collector object');


  $v = new cqValidatorCollectorByName(array(
      'invalid_ids' => array($ivan_tanev_id),
  ));

  try
  {
    $v->clean('ivan.tanev');
    $t->fail('cqValidatorCollectorByName returns error on disallowed IDs');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('cqValidatorCollectorByName returns error on disallowed IDs');
  }
