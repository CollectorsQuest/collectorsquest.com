<?php

require_once __DIR__.'/../../bootstrap/model.php';

$t = new lime_test(2, array('output' => new lime_output_color(), 'error_reporting' => true));


$t->diag('::getRandomModelObject()');

  $collector = cqTest::getModelObject('Collector', true);
  $t->isa_ok($collector, 'Collector');

  $collection = cqTest::getModelObject('CollectorCollection', false);
  $t->isa_ok($collection, 'CollectorCollection');

$t->diag('::getNewModel()');

