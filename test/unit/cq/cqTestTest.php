<?php

require_once __DIR__.'/../../bootstrap/model.php';

$t = new lime_test(2, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('::getRandomModelObject()');

  $collector = cqTest::getModelObject('Collector', true);
  $t->isa_ok($collector, 'Collector');

  $multimedia = cqTest::getModelObject('iceModelMultimedia', false);
  $t->isa_ok($multimedia, 'iceModelMultimedia');

$t->diag('::getNewModel()');

