<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(4, array('output' => new lime_output_color(), 'error_reporting' => true));

cqTest::resetClasses(array('Collector'));

$t->diag('::setEmail()');

  $collector = cqTest::getModelObject('Collector', true);
  $collector->setEmail('nobody@collectorsquest.com');
  $t->is($collector->getEmail(), 'nobody@collectorsquest.com');

$t->diag('::getCollections(), ::countCollections()');

  /** @var $q CollectorQuery */
  $q = CollectorQuery::create()
     ->joinWith('CollectorCollection', Criteria::RIGHT_JOIN)
     ->addAscendingOrderByColumn('RAND()');

  $collector = $q->findOne();
  $t->isa_ok($collector->getCollections(),  'PropelObjectCollection');
  $t->is($collector->countCollections() > 0,  true);
  $t->is($collector->getCollections()->count(), $collector->countCollections());
