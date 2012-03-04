<?php

$app = 'legacy';
include(__DIR__ . '/../../bootstrap/functional.php');

cqTest::resetClasses('Collections');

$t = new lime_test(5, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser(), $t);

$browser->
  get('/collections/index')->

  with('request')->begin()->
    isParameter('module', 'collections')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('h1', '/Collections/')->
    checkElement('#footer', '/Made by hand in NY/')->
  end()
;
