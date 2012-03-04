<?php

$app = 'legacy';
include(__DIR__ . '/../../bootstrap/functional.php');

$t = new lime_test(5, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser(), $t);

cqTest::resetClasses('Collector');
cqTest::loadFixtures(array('01_test_collectors/'));

$browser->
  login('ivan.tanev', 'ivanpass')->

  get('/manage/profile')->
  with('request')->begin()->
    isParameter('module', 'manage')->
    isParameter('action', 'profile')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('h1', '/Your Profile/')->
    checkElement('#footer', '/Made by hand in NY/')->
  end()
;
