<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(12, new lime_output_color());

// Reset all tables we will be working on
cqTest::resetTables(array('collector', 'collector_profile'));
cqTest::loadFixtureDirs('01_test_collectors');

$t->diag('::setWebsite(), ::getWebsite(), ::getWebsiteUrl()');

  $collector_profile = CollectorProfilePeer::doSelectOne(new Criteria());

  $collector_profile->setWebsite('http://www.collectorsquest.com');
  $t->is($collector_profile->getWebsite(), 'collectorsquest.com');

  $collector_profile->setWebsite('www.collectorsquest.com');
  $t->is($collector_profile->getWebsite(), 'collectorsquest.com');
  $t->is($collector_profile->getWebsiteUrl(), 'http://collectorsquest.com');

  $collector_profile->setWebsite('www.collectorsquestwww.com');
  $t->is($collector_profile->getWebsite(), 'collectorsquestwww.com');
  $t->is($collector_profile->getWebsiteUrl(), 'http://collectorsquestwww.com');

$t->diag('::setBirthday()');

  $collector_profile = CollectorProfilePeer::doSelectOne(new Criteria());

  $collector_profile->setBirthday('1983-03-25');
  $t->is($collector_profile->getBirthday(DateTime::ISO8601), '1983-03-25T00:00:00-0500', 'Testing for full dates');

  $collector_profile->setBirthday('0000-03-25');
  $t->is($collector_profile->getBirthday(DateTime::ISO8601), '0000-03-25T00:00:00-0500', 'Testing for dates without the year');

  $birthday = array('year' => '0000', 'month' => '03', 'day' => '25');
  $collector_profile->setBirthday($birthday);
  $t->is($collector_profile->getBirthday(DateTime::ISO8601), '0000-03-25T00:00:00-0500', 'Testing for dates from array');

  $birthday = array('month' => '03', 'day' => '25');
  $collector_profile->setBirthday($birthday);
  $t->is($collector_profile->getBirthday(DateTime::ISO8601), '0000-03-25T00:00:00-0500', 'Testing for dates from array');

  $birthday = array('year' => '0000', 'month' => '03');
  $collector_profile->setBirthday($birthday);
  $t->is($collector_profile->getBirthday(DateTime::ISO8601), '0000-03-01T00:00:00-0500', 'Testing for dates from array');

$t->diag('::setGender()');

  $collector_profile = CollectorProfilePeer::doSelectOne(new Criteria());

  $collector_profile->setGender('male');
  $t->is($collector_profile->getGender(), 'm', 'Testing for male');
  $collector_profile->setGender('boy');
  $t->is($collector_profile->getGender(), 'm', 'Testing for male');
  $collector_profile->setGender('m');
  $t->is($collector_profile->getGender(), 'm', 'Testing for male');

  $collector_profile->setGender('female');
  $t->is($collector_profile->getGender(), 'f', 'Testing for female');
  $collector_profile->setGender('girl');
  $t->is($collector_profile->getGender(), 'f', 'Testing for female');
  $collector_profile->setGender('f');
  $t->is($collector_profile->getGender(), 'f', 'Testing for female');

$t->diag('::getAge()');

  $collector_profile = CollectorPeer::retrieveBySlug('ivan-tanev')->getProfile();

  $t->is($collector_profile->getAge('2012-03-14'), 24, 'getAge() properly calculates age when current time given as argument');
  $t->isa_ok($collector_profile->getAge(), 'integer', 'Get age will calculate the age to the current system time when no arguments given');
