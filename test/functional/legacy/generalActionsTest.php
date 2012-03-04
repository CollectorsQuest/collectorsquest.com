<?php

$app = 'legacy';
include(__DIR__ . '/../../bootstrap/functional.php');

$t = new lime_test(7, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser(), $t);

$browser->
  get('/general/index')->

  with('request')->begin()->
    isParameter('module', 'general')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('h2', '/ARTICLES/')->
    checkElement('h2', '/VIDEOS/')->
    checkElement('h2', '/FEATURED WEEK/')->
    checkElement('#footer', '/Made by hand in NY/')->
  end()
;
