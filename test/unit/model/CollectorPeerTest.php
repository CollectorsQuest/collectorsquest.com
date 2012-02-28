<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(4, array('output' => new lime_output_color(), 'error_reporting' => true));

// Reset all tables we will be working on
cqTest::resetTables(array(
  'collector', 'collector_profile',
  'collector_email', 'collector_geocache'
));

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

$t->diag('::retrieveByDistance()');

  $pks = CollectorPeer::retrieveByDistance(11201, 100, false);
  $t->is($pks, CollectorPeer::retrieveByPKs(array(1, 4)));
