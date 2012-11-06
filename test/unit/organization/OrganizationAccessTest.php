<?php

include(__DIR__.'/../../bootstrap/model.php');
require_once dirname(__FILE__).'/../../../lib/organization/OrganizationAccess.class.php';

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('Testing lib/organization/OrganizationAccess.class.php');


cqTest::resetClasses(array('Collector'));
cqTest::resetClasses(array('Organization'));
cqtest::loadFixtures(array('01_test_collectors', '05_test_organizations'));

$organization = OrganizationQuery::create()
  ->findOneBySlug('test');
$collector = CollectorQuery::create()
  ->findOneByUsername('ivan.tanev');


$t->diag('Test ::addMember()');

  OrganizationAccess::addMember($organization, $collector);

  $t->is($organization->getNbMembers(), 1,
    'addMember works as expected');
  $t->ok($organization->isMember($collector),
    'addMember works as expected');


  OrganizationAccess::addMember($organization, $collector);

  $t->is($organization->getNbMembers(), 1,
    'trying to add the same collector twice doesn\'t work');
  $t->ok($organization->isMember($collector),
    'trying to add the same collector twice doesn\'t work');