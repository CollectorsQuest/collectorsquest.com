<?php

include(__DIR__.'/../../bootstrap/model.php');
require_once dirname(__FILE__).'/../../../lib/organization/OrganizationAccess.class.php';

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));

$t->diag('Testing lib/organization/OrganizationAccess.class.php');


cqTest::resetClasses(array('Collector'));
cqTest::resetClasses(array('Organization'));
cqtest::loadFixtures(array('01_test_collectors', '05_test_organizations'));

$organization = OrganizationQuery::create()->findOneBySlug('test');
$collector = CollectorQuery::create()->findOneByUsername('ivan.tanev');


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

  $t->diag('Test ::createMembershipRequest() for moderated organizations');
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

  $t->diag('Test ::createMembershipRequest() for open organizations');
  $organization = OrganizationQuery::create()->findOneBySlug('open');
  $t->ok(!$organization->isMembershipRequested($collector));
  OrganizationAccess::createMembershipRequest($organization, $collector);
  $t->ok(!$organization->isMembershipRequested($collector));
  $t->ok($organization->isMember($collector),
    'Calling ::createMembershipRequest() for an open organization directly adds the collector to the organization');

   $t->ok(!$organization->isMembershipRequested($collector_2));
  OrganizationAccess::createMembershipRequest($organization, $collector_2, $is_invitation = true);
  $t->ok($organization->isMembershipRequested($collector_2));
  $t->ok(!$organization->isMember($collector_2),
    'Calling ::createMembershipRequest() does not directly add the member if it\'s an invitation');


  $t->diag('Test ::createMembershipRequest() for private organizations');
  $organization = OrganizationQuery::create()->findOneBySlug('private');
  $t->ok(!$organization->isMembershipRequested($collector));
  try {
    OrganizationAccess::createMembershipRequest($organization, $collector);
    $t->fail('Calling ::createMembershipRequest() for an private organization throws a named exception');
  }  catch (OrganizationAccessMembershipRequestDeniedForPrivateOrganization $e) {
    $t->pass('Calling ::createMembershipRequest() for an private organization throws a named exception');
  }
  $t->ok(!$organization->isMembershipRequested($collector));
  $t->ok(!$organization->isMember($collector));

  try {
    OrganizationAccess::createMembershipRequest($organization, $collector, $is_invitation = true);
    $t->pass('Calling ::createMembershipRequest() with $is_invitation set doesn\'t throw an exception');
  }  catch (OrganizationAccessException $e) {
    $t->fail('Calling ::createMembershipRequest() with $is_invitation set doesn\'t throw an exception');
  }
  $t->ok($organization->isMembershipRequested($collector));
  $t->ok(!$organization->isMember($collector));