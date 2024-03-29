<?php

define('TEST_CQ_NEXT_ACCESS_FILTER', true);

$app = 'frontend';
include(dirname(__FILE__).'/../../bootstrap/functional.php');

cqTest::resetClasses('Collector');
cqTest::loadFixtures(array('01_test_collectors'));

$t = new lime_test(22, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser(), $t);


$browser
  ->info('Testing the CqNext access filter');
sfConfig::set('app_cqnext_auto_login_time_limit', '5 minutes');
sfConfig::set('app_cqnext_auto_login_parameter_name', 'i');

$browser
  /* */
  ->info('  1. Unauthorized users are forwarded to the countdown action')
  ->get('/general/index')
  ->with('request')->begin()
    ->isParameter('module', 'general')
    ->isParameter('action', 'index')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h1', '/The New CollectorsQuest.com!/i')
  ->end();

  /* */
$collector = CollectorPeer::retrieveBySlug('ivan-tanev');
$collector->setCqnextAccessAllowed(true);
$collector->save();

$browser
  ->info('  2. Accessing an url with the proper hash auto-logins you')
  ->get('/general/index?i='.urlencode($collector->getAutoLoginHash('v1')))
  ->with('response')->isRedirected(true)
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'general')
    ->isParameter('action', 'index')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h1', '!/The New CollectorsQuest.com!/i')
  ->end()
  ->logout()
  ->with('response')->isRedirected(true)
  ->followRedirect();

  /* */
$browser->clearSession();

$browser
  ->info('  3. Accessing an url with the proper hash after time limit does not login you')
  ->get('/general/index?i='.urlencode($collector->getAutoLoginHash('v1', strtotime('-10 minutes'))))
  ->with('response')->isRedirected(false)
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h1', '/The New CollectorsQuest.com!/i')
  ->end();

  /* */
$browser->clearSession();

$browser
  ->info('  4. After one successful login, even if you log out access is preserved')
  ->get('/general/index?i='.urlencode($collector->getAutoLoginHash('v1')))
  ->with('response')->isRedirected(true)
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'general')
    ->isParameter('action', 'index')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h1', '!/The New CollectorsQuest.com!/i')
  ->end()
  ->logout()
  ->get('/general/index')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h1', '!/The New CollectorsQuest.com!/i')
  ->end()
  ;
