<?php

include(dirname(__FILE__).'/../../bootstrap/model.php');

$t = new lime_test(8, new lime_output_color());

// Reset all tables we will be working on
cqTest::resetTables(array('collector', 'collector_profile'));

$t->diag('::createFromArray()');

  $data = $data = array(
    'username' => uniqid(),
    'password' => IceStatic::getUniquePassword(),
    'display_name' => 'Kiril Angov',
    'email' => 'kangov@collectorsquest.com',

    'birthday' => '03/25/1983',
    'gender' => 'male',
    'website' => 'http://www.collectorsquest.com'
  );
  $collector = CollectorPeer::createFromArray($data);
  $collector_profile = $collector->getProfile();

  $t->isa_ok($collector, 'Collector');
  $t->is($collector->getDisplayName(), 'Kiril Angov', 'Checking the display name');
  $t->is($collector->getEmail(), 'kangov@collectorsquest.com', 'Checking the email');
  $t->is($collector_profile->getGender(), 'm', 'Checking the gender');
  $t->is($collector_profile->getBirthday(DateTime::ISO8601), '1983-03-25T00:00:00-0500', 'Checking the birthday');
  $t->is($collector_profile->getWebsite(), 'collectorsquest.com', 'Checking the website');

$t->diag('::retrieveByDistance()');

  $pks = CollectorPeer::retrieveByDistance(24712, 50, true);
  $t->is($pks, array(660, 1372));

  $pks = CollectorPeer::retrieveByDistance(24712, 50, false);
  $t->is($pks, CollectorPeer::retrieveByPKs(array(660, 1372)));

// Reset all tables we will be working on
cqTest::resetTables(array('collector', 'collector_profile'));
