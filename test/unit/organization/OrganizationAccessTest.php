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


$t->diag('Test ::createMembershipRequest()');

  $t->ok(!$organization->isMembershipRequested($collector));
  try {
    OrganizationAccess::createMembershipRequest($organization, $collector);
    $t->fail('::createMembershipRequest() throws an exception when collector is already member of the organization');
  }  catch (OrganizationAccessMembershipRequestAlreadyMemberException $e) {
    $t->pass('::createMembershipRequest() throws an exception when collector is already member of the organization');
  }
  $t->ok(!$organization->isMembershipRequested($collector));

  $collector_2 = CollectorQuery::create()
    ->findOneByUsername('ivan.ivanov');
  $t->ok(!$organization->isMembershipRequested($collector_2));
  $t->ok(OrganizationAccess::createMembershipRequest($organization, $collector_2),
    '::createMembershipRequest() successfully creates a new request');
  $t->ok($organization->isMembershipRequested($collector_2));

  try {
    OrganizationAccess::createMembershipRequest($organization, $collector_2);
    $t->fail('::createMembershipRequest() throws an exception when collector is already member of the organization');
  }  catch (OrganizationAccessMembershipRequestAlreadyPendingException $e) {
    $t->pass('::createMembershipRequest() throws an exception when collector is already member of the organization');
  }
