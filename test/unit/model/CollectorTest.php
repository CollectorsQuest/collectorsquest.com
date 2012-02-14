<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(1, array('output' => new lime_output_color(), 'error_reporting' => true));

// Reset all tables we will be working on
cqTest::resetTables(array('collector', 'collector_archive'));

$t->diag('::setEmail()');

  $collector = CollectorQuery::create()->findOne();
  $collector->setEmail('nobody@collectorsquest.com');
  $t->is($collector->getEmail(), 'nobody@collectorsquest.com');
