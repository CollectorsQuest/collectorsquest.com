<?php

/**
 * OrganizationAccess gives methods for modifying member access to organizations
 *
 * @package     organization
 * @author      Ivan Plamenov Tanev aka Crafty_Shadow <vankata.t@gmail.com>
 */
class OrganizationAccess
{

  /**
   * Add a member to an organization
   *
   * @param     Organization $organization
   * @param     Collector $collector
   * @param     OrganizationMembershipPeer::TYPE $type
   * @param     PropelPDO $con
   *
   * @return    boolean
   */
  public static function addMember(
    Organization $organization,
    Collector $collector,
    $type = OrganizationMembershipPeer::TYPE_MEMBER,
    PropelPDO $con = null
  ) {
    if (!$organization->isMember($collector))
    {
      $membership = new OrganizationMembership();
      $membership->setOrganizationId($organization->getId());
      $membership->setCollectorId($collector->getId());
      $membership->setType($type);
      $membership->save($con);
    }

    return true;
  }

}

