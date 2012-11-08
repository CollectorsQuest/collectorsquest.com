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
   * @return    boolean True if a member was added, or false if he was already a member
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
      // TODO: Send welcome to organization email

      return true;
    }
    else
    {
      return false;
    }
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
   * @throws    OrganizationAccessMembershipRequestDeniedForPrivateOrganization
   */
  public static function createMembershipRequest(
    Organization $organization,
    Collector $collector,
    $is_invitation = false,
    $note = '',
    PropelPDO $con = null
  ) {

    if (OrganizationPeer::ACCESS_OPEN == $organization->getAccess() && !$is_invitation)
    {
      return self::addMember(
        $organization,
        $collector,
        OrganizationMembershipPeer::TYPE_MEMBER,
        $con
      );
    }

    if (OrganizationPeer::ACCESS_PRIVATE == $organization->getAccess() && !$is_invitation)
    {
      throw new OrganizationAccessMembershipRequestDeniedForPrivateOrganization();
    }

    if ($organization->isMember($collector))
    {
      throw new OrganizationAccessMembershipRequestAlreadyMemberException();
    }

    $existing_request = $organization->isMembershipRequested($collector, $con);

    // there is no existing request, so we will create a new one
    if (!$existing_request)
    {
      $request = new OrganizationMembershipRequest();
      $request->setOrganizationId($organization->getId());
      $request->setCollectorId($collector->getId());
      $request->setIsInvitation($is_invitation);
      $request->setRequestNote($note);
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

