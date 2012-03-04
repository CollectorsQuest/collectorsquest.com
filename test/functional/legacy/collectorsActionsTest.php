<?php

$app = 'legacy';
include(__DIR__ . '/../../bootstrap/functional.php');

cqTest::resetClasses('Collector');

$t = new lime_test(5, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser(), $t);

$browser->
  get('/collectors/index')->

  with('request')->begin()->
    isParameter('module', 'collectors')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('h1', '/Collectors/')->
    checkElement('#footer', '/Made by hand in NY/')->
  end()
;
