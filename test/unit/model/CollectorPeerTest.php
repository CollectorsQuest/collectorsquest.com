<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(8, array('output' => new lime_output_color(), 'error_reporting' => true));

// Reset all tables we will be working on
cqTest::resetTables(array(
  'collector', 'collector_profile',
  'collector_email', 'collector_geocache'
));
cqTest::loadFixtures('01_test_collectors/');

$t->diag('::createFromArray()');

  $data = $data = array(
    'username' => uniqid(),
    'password' => IceStatic::getUniquePassword(),
    'display_name' => 'Kiril Angov',
    'email' => 'kangov@collectorsquest.com',
  );
  $collector = CollectorPeer::createFromArray($data);
  $collector_profile = $collector->getProfile();

  $t->isa_ok($collector, 'Collector');
  $t->is($collector->getDisplayName(), 'Kiril Angov', 'Checking the display name');
  $t->is($collector->getEmail(), 'kangov@collectorsquest.com', 'Checking the email');

$t->diag('::retrieveByHashTimeLimited()');

  $time_of_generation = time();
  $collector = CollectorPeer::doSelectOne(new Criteria());
  $hash = $collector->getAutoLoginHash('v1', $time_of_generation);
  $id = $collector->getId();

  $collector = CollectorPeer::retrieveByHashTimeLimited(
    $hash, '+15 seconds', $current_time = strtotime('+10 seconds', $time_of_generation)
  );
  $t->isa_ok($collector, 'Collector', 'retrieveByHashTimeLimited returns the object when in the limit');
  $t->is($collector->getId(), $id, 'retrieveByHashTimeLimited returns the right object');

  $collector = CollectorPeer::retrieveByHashTimeLimited(
    $hash, '+5 seconds', $current_time = strtotime('+10 seconds', $time_of_generation)
  );
  $t->isa_ok($collector, 'NULL', 'retrieveByHashTimeLimited returns null when the object has passed its time limit');

$t->diag('::retrieveByUsername()');

  $t->isa_ok(CollectorPeer::retrieveByUsername('ivan.tanev'), 'Collector');
  $t->isa_ok(CollectorPeer::retrieveByUsername('dfhjFKdhfalfhas'), 'NULL');
