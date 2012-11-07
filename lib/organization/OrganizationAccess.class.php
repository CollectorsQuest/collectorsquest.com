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

    // TODO: Send welcome to organization email

    return true;
  }

  /**
   * Create a membership request for a particular organization.
   *
   * If the requesting collector is already a member, or a membership request already
   * exists for this organization an exception with the specific error is thrown
   *
   * @param     Organization $organization
   * @param     Collector $collector
   * @param     boolean $is_invitation
   * @param     PropelPDO $con
   *
   * @return    boolean
   * @throws    OrganizationAccessMembershipRequestAlreadyMemberException
   * @throws    OrganizationAccessMembershipRequestAlreadyPendingException
   * @throws    OrganizationAccessMembershipRequestAlreadyApprovedException
   * @throws    OrganizationAccessMembershipRequestAlreadyDeniedException
   * @throws    OrganizationAccessException
   */
  public static function createMembershipRequest(
    Organization $organization,
    Collector $collector,
    $is_invitation = false,
    PropelPDO $con = null
  ) {

    if ($organization->isMember($collector))
    {
      throw new OrganizationAccessMembershipRequestAlreadyMemberException();
    }

    $existing_request = OrganizationMembershipRequestQuery::create()
      ->filterByOrganization($organization)
      ->filterByCollectorRelatedByCollectorId($collector)
      ->orderById(Criteria::DESC) // alternative to an "order by created at" cond
      ->findOne($con);

    // there is no existing request, so we will create a new one
    if (!$existing_request)
    {
      $request = new OrganizationMembershipRequest();
      $request->setOrganizationId($organization->getId());
      $request->setCollectorId($collector->getId());
      $request->setIsInvitation($is_invitation);
      $request->save($con);

      if ($is_invitation)
      {
        // TODO: Send join invitation email to collector
      }
      else
      {
        // TODO: Send join request email to organization admins
      }

      return true;
    }
    else
    {
      switch($existing_request->getStatus())
      {
        case (OrganizationMembershipRequestPeer::STATUS_PENDING):
          throw new OrganizationAccessMembershipRequestAlreadyPendingException();

        case (OrganizationMembershipRequestPeer::STATUS_APPROVED):
          throw new OrganizationAccessMembershipRequestAlreadyApprovedException();

        case (OrganizationMembershipRequestPeer::STATUS_DENIED):
          throw new OrganizationAccessMembershipRequestAlreadyDeniedException();

        default:
          throw new OrganizationAccessMembershipRequestAlreadyExistsException();
      }
    }
  }

}

