<?php
$app = 'legacy';

include(__DIR__.'/../../bootstrap/functional.php');

cqTest::resetTables(array('collector', 'collector_profile', 'collector_email'));
cqTest::loadFixtureDirs(array('01_test_collectors'));


$browser = new myTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));

$browser
  ->info('Testing user registration')
  ->loadFormData('legacy/user_registration_test.yml')
  ->setTester('propel', 'sfTesterPropel');


$browser
  /* */
  ->info('  1. Unauthenticated user can access singup step 1 but not 2')
  ->get('/collector/signup')
  ->with('request')->begin()
    ->isParameter('module', 'collector')
    ->isParameter('action', 'signup')
  ->end()
  ->with('user')->isAuthenticated(false)
  ->get('collector/signup/2')
  ->with('user')->isAuthenticated(false)
  ->with('response')->isRedirected(true)
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'collector')
    ->isParameter('action', 'signup')
    ->isParameter('step', 1)
  ->end()

  /* */
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

  /* */
  ->info('  3. Submit form with errorneous data (existing username/email)')
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

  /* */
  ->info('  4. Submit form with errorneous data (invalid symbols in username)')
  ->get('/collector/signup')
  ->fillForm('collectorstep1', array(
      'username' => '123ivan *tanev',
    ), 'TestCollectorSignupStep1Form')
  ->click('*[type=submit]')
  ->with('form')->begin()
    ->hasErrors(1)
    ->isError('username', 'invalid')
  ->end()


  /* */
  ->info('  5. Submit form with data for new user')
  ->get('/collector/signup')
  ->fillForm('collectorstep1', array(), 'TestCollectorSignupStep1Form')
  ->click('*[type=submit]')
  ->with('form')->hasErrors(0)
  ->with('response')->isRedirected(true)
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'collector')
    ->isParameter('action', 'signup')
    ->isParameter('step', 2)
  ->end()
  ->with('user')->isAuthenticated()
  ->with('propel')->check('Collector', array(
      'username' => $browser->getFormFixture(
                      'TestCollectorSignupStep1Form', 'username')
  ))

  /* */
  ->info('  6. User is not allowed to access secure pages before completing registration')
  ->get('collector/me')
  ->with('response')->isRedirected(false)
  ->with('response')->matches('/Username/')

  ->info('  7. User is allowed to access non-secure pages before completing registration')
  ->get('/')
  ->with('request')->begin()
    ->isParameter('module', 'general')
    ->isParameter('action', 'index')
  ->end()


  /* */;
