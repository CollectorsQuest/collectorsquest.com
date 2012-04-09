<?php

$app = 'legacy';
include(__DIR__ . '/../../bootstrap/functional.php');

$t = new lime_test(5, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser(), $t);

$browser->
  get('/community/index')->

  with('request')->begin()->
    isParameter('module', 'community')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Community Sneak Peek/')->
    checkElement('#footer', '/Made by hand in NY/')->
  end()
;
