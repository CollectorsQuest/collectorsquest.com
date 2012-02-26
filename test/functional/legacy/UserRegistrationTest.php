<?php
$app = 'legacy';

include(__DIR__.'/../../bootstrap/functional.php');

cqTest::resetTables(array(
    'collector',
    'collector_extra_property',
    'collector_profile',
    'collector_profile_extra_property',
    'collector_email'));
cqTest::loadFixtureDirs(array('01_test_collectors'));


$browser = new myTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));

$browser
  ->info('Testing user registration:')
  ->loadFormFixtures('legacy/user_registration_test.yml')
  ->setTester('propel', 'sfTesterPropel');

$browser
  /* * /
  ->info('  1. Unauthenticated user can access singup step 1 but not 2')
  ->get('/collector/signup')
  ->with('user')->isAuthenticated(false)
  ->with('request')->begin()
    ->isParameter('module', 'collector')
    ->isParameter('action', 'signup')
  ->end()
  ->with('response')->isRedirected(false)
  ->get('collector/signup/2')
  ->with('user')->isAuthenticated(false)
  ->with('response')->isRedirected(true)
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'collector')
    ->isParameter('action', 'signup')
    ->isParameter('step', 1)
  ->end()

  /* * /
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

  /* * /
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

  /* * /
  ->info('  4. Submit form with errorneous data (invalid symbols in username)')
  ->get('/collector/signup')
  ->fillForm('collectorstep1', array(
      'username' => '123ivan *tanev',
    ), 'CollectorSignupStep1')
  ->click('*[type=submit]')
  ->with('form')->begin()
    ->hasErrors(1)
    ->isError('username', 'invalid')
  ->end()


  /* */
  ->info('  5. Submit form with data for new user')
  ->get('/collector/signup')
  ->fillForm('collectorstep1', array(), 'CollectorSignupStep1')
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
      'username' => $browser->getFormFixture('CollectorSignupStep1', 'username')
  ))

  /* */
  ->info('  6. User is allowed to access non-secure pages before completing registration')
  ->get('/')
  ->with('request')->begin()
    ->isParameter('module', 'general')
    ->isParameter('action', 'index')
  ->end()

  /* */
  ->info('  7. User is forwarded to singup step 2 when trying to access a secure page before completing registration')
  ->get('/collector/me')
  ->with('response')->isRedirected(true)
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'collector')
    ->isParameter('action', 'signup')
    ->isParameter('step', 2)
  ->end()


  /* */
  ->info('  8. User is redirected to step 2 if it has not been completed')
  ->get('/collector/signup/3')
  ->with('response')->isRedirected(true)
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'collector')
    ->isParameter('action', 'signup')
    ->isParameter('step', 2)
  ->end()

  /* */
  ->info('  9. Submiting empty step 2 form')
  ->get('/collector/signup/2')
  ->click('*[type=submit]')
  ->with('form')->begin()
    ->hasErrors(3)
    ->isError('collector_type')
    ->isError('about_what_you_collect')
    ->isError('about_purchase_per_year')
  ->end()

  /* */
  ->info(' 10. Submit proper step2 form')
  ->get('/collector/signup/2')
  ->fillForm('collectorstep2', array(), 'CollectorSignupStep2')
  ->click('*[type=submit]')
  ->with('form')->hasErrors(0)
  ->with('response')->isRedirected(true)
  ->followRedirect()
  ->with('request')->begin()
    ->isparameter('module', 'collector')
    ->isparameter('action', 'signup')
    ->isparameter('step', 3)
  ->end();
$test_collector = CollectorPeer::retrieveBySlug('test-collector');
$test_collector_profile = $test_collector->getProfile();
$browser->test()->is($test_collector_profile->getAboutWhatYouCollect(),
  $browser->getFormFixture('CollectorSignupStep2', 'about_what_you_collect'),
  'Collector profile data is successfully set in step 2');

  /* */
$browser
  ->info(' 11. Collector is redirected to step 3 when trying to access a secure page')
  ->get('/collector/me')
  ->with('response')->isRedirected(true)
  ->followRedirect()
   ->with('request')->begin()
    ->isparameter('module', 'collector')
    ->isparameter('action', 'signup')
    ->isparameter('step', 3)
  ->end()

  /* */
  ->info(' 12. Collector can access step 3 form after completing step 2')
  ->get('/collector/signup/3')
  ->with('response')->isRedirected(false)
  ->with('request')->begin()
    ->isparameter('module', 'collector')
    ->isparameter('action', 'signup')
    ->isparameter('step', 3)
  ->end()
  /* */;
