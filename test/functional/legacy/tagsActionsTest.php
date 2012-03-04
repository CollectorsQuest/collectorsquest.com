<?php

$app = 'legacy';
include(__DIR__ . '/../../bootstrap/functional.php');

$t = new lime_test(23, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser(), $t);

$browser->
  get('/tags')->

  with('request')->begin()->
    isParameter('module', 'tags')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('h1', 'Tag Cloud')->
    checkElement('#footer', '/Made by hand in NY/')->
  end()
;

$browser->
  get('/tags/collections')->

  with('request')->begin()->
  isParameter('module', 'tags')->
  isParameter('action', 'tagCloud')->
  end()->

  with('response')->begin()->
  isStatusCode(200)->
  checkElement('h1', 'Collections')->
  checkElement('#footer', '/Made by hand in NY/')->
  end()
;

$browser->
  get('/tags/collectibles')->

  with('request')->begin()->
  isParameter('module', 'tags')->
  isParameter('action', 'tagCloud')->
  end()->

  with('response')->begin()->
  isStatusCode(200)->
  checkElement('h1', 'Collectibles')->
  checkElement('#footer', '/Made by hand in NY/')->
  end()
;

$browser->
  get('/tags/countries')->

  with('request')->begin()->
  isParameter('module', 'tags')->
  isParameter('action', 'tagCloud')->
  end()->

  with('response')->begin()->
  isStatusCode(200)->
  checkElement('h1', 'Countries')->
  checkElement('#footer', '/Made by hand in NY/')->
  end()
;

$browser->
  get('/tags/something')->
  with('response')->isRedirected(true)->
  followRedirect()->

  with('request')->begin()->
  isParameter('module', 'general')->
  isParameter('action', 'error404')->
  end()
;
