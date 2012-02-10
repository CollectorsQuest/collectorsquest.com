<?php

require_once dirname(__FILE__).'/../../bootstrap/unit.php';

$t = new lime_test(3, array('output' => new lime_output_color(), 'error_reporting' => true));


$t->diag('::getRandomModelObject()');

  $collector = cqTest::getRandomModelObject('Collector');
  $t->isa_ok($collector, 'Collector');

  $collection = cqTest::getRandomModelObject('CollectorCollection');
  $t->isa_ok($collection, 'CollectorCollection');

$t->diag('::getNewModel()');

  $collection = cqTest::getNewModelObject('CollectorCollection', false);
  $t->isa_ok($collection, 'CollectorCollection');
