<?php
include dirname(__FILE__) . '/../../bootstrap/frontend.php';

cqTest::resetClasses('Collector');
cqTest::loadFixtures(array('01_test_collectors/'));

//$t = new lime_test(4, array('output' => new lime_output_color(), 'error_reporting' => true));
$browser = new cqTestFunctional(new sfBrowser());
