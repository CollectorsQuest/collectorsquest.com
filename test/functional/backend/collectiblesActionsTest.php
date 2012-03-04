<?php

$app = 'backend';
include(__DIR__.'/../../bootstrap/functional.php');

cqTest::resetClasses(array('Collectible', 'CollectibleCollectible'));

$t = new lime_test(4, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser(), $t);

$browser->
  get('/collectibles/index')->

  with('request')->begin()->
    isParameter('module', 'collectibles')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/\d+ results\s+(page 1\/\d+)/')->
  end()
;
