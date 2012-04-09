<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(8, array('output' => new lime_output_color(), 'error_reporting' => true));

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


$t->diag("::getSalt()");

  $new_collector = new Collector();
  $salt = $new_collector->getSalt();
  $t->is(strlen($salt), 32,
    "getSalt() will generate a new md5 hash on first run");
  $t->is($salt, $new_collector->getSalt(),
    'getSalt() returns the same salt after first run');


$t->diag("::checkPassword()");
  $new_collector = new Collector();
  $new_collector->setPassword('ggbg');
  $t->is($new_collector->checkPassword('not-ggbg'), false,
    'checkPassword() returns false for wrong password');
  $t->is($new_collector->checkPassword('ggbg'), true,
    'checkPassword() return ture for right password');