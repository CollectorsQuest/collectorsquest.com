<?php
$app = 'legacy';

include(__DIR__.'/../../bootstrap/functional.php');

cqTest::resetTables(array('collector', 'collector_profile', 'collector_email'));
cqTest::loadFixtureDirs(array('01_test_collectors'));


$browser = new myTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));

$browser
  ->loadFormData('legacy/user_registration_test.yml')


  ->info('Testing user registration')

  ->info('  1. User is not authenticated before we start registration')
  ->get('/collector/signup')
  ->with('request')->begin()
    ->isParameter('module', 'collector')
    ->isParameter('action', 'signup')
  ->end()
  ->with('user')->isAuthenticated(false)

  ->info('  2. Submitting an empty form')
  ->get('/collector/signup')
  ->click('*[type=submit]')
  ->with('form')->begin()
    ->hasErrors(4)
    ->isError('username', 'required')
    ->isError('display_name', 'required')
    ->isError('password', 'required')
    ->isError('email', 'required')
  ->end()

  ->info('  2. Submit form with data for existing user')
  ->get('/collector/signup')
  ->fillForm('collectorstep1', array(
      'username' => 'ivan.tanev',
      'display_name' => 'Ivan Tanev',
      'email' => 'vankata.t@example.com',
      'password' => 'password',
      'password_again' => 'password',
    ))
  ->click('*[type=submit]')
  ->with('form')->begin()
    ->hasErrors(2)
    ->isError('username', 'invalid')
    ->isError('email', 'invalid')
  ->end()

  ->info('  3. Submit form with data for new user')
  ->get('/collector/signup')
  ->fillForm('collectorstep1', array(), 'TestCollectorStep1')
  ->click('*[type=submit]')
  ->with('form')->hasErrors(0)

;
