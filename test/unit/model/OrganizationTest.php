<?php

include(__DIR__.'/../../bootstrap/model.php');
require_once dirname(__FILE__).'/../../../lib/model/organizations/Organization.php';

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('Testing lib/model/organizations/Organization.php');


cqTest::resetClasses(array('Collector'));
cqTest::resetClasses(array('Organization'));
cqtest::loadFixtures(array('01_test_collectors', '05_test_organizations'));

$organization = OrganizationQuery::create()
  ->findOneBySlug('test');
$collector = CollectorQuery::create()
  ->findOneByUsername('ivan.tanev');


$t->ok(!$organization->isMembershipRequested($collector));
OrganizationAccess::createMembershipRequest($organization, $collector);
$t->ok($organization->isMembershipRequested($collector));


$t->ok(!$organization->isMember($collector));
OrganizationAccess::addMember($organization, $collector);
$t->ok($organization->isMember($collector));