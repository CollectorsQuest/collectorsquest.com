<?php

/**
 * organization actions.
 *
 * @package    CollectorsQuest
 * @subpackage organization
 * @author     Collectors Quest, Inc.
 */
class organizationActions extends cqFrontendActions
{

  /**
   * Display an organization's main profile
   */
  public function executeIndex(sfWebRequest $request)
  {
    /* @var $organization Organization */
    $organization = $this->getRoute()->getObject();
    $organization->incrementNumViews($andSave = true);

    $this->organization = $organization;

    return sfView::SUCCESS;
  }

  /**
   * Action Join
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeJoin(sfWebRequest $request)
  {
    /* @var $organization Organization */
    $organization = $this->getRoute()->getObject();
    $collector = $this->getCollector();

    // if the user is authenticated and not member of the organization
    if ($this->getUser()->isAuthenticated() && !$organization->isMember($collector))
    {
      // and if the organization has open access
      if (OrganizationPeer::ACCESS_OPEN == $organization->getAccess())
      {
        // directly make the collector a member of the organization
        OrganizationAccess::addMember($organization, $collector);

        $this->getUser()->setFlash('success', sprintf(
          'You are now part of the %s organization. Welcome!',
          $organization->getName()
        ));

        // and redirect him/her to the organization page
        return $this->redirect('organization_by_slug', $organization);
      }

      // otherwize try to add a membership request for the collector
      try
      {
        OrganizationAccess::createMembershipRequest($organization, $collector);

        $this->getUser()->setFlash('success', 'Your request was sucessfully submitted. Now you just have to wait for your reply.');
      }
      catch (OrganizationAccessMembershipRequestAlreadyPendingException $e)
      {
        $this->getUser()->setFlash('error', 'You have already requested membership for this organization. Please wait for your reply.'
        );
      }
      catch (OrganizationAccessMembershipRequestAlreadyDeniedException $e)
      {
        $this->getUser()->setFlash('error', 'You have already been denied membership for this organization. Sorry.'
        );
      }
      catch (OrganizationAccessMembershipRequestDeniedForPrivateOrganization $e)
      {
        $this->getUser()->setFlash('error', 'You cannot request membership for this organization. Only its moderators can invite collectors to join.'
        );
      }
    }
    else
    {
      $this->getUser()->setFlash('info', sprintf(
        'You need to register an account with us before you can join the %s organization.',
        $organization->getName()
      ));

      // for non-authenticated users, forward to the signup page with the referral
      // code pre-populated if the organization has open access
      if (OrganizationPeer::ACCESS_OPEN == $organization->getAccess())
      {
        return $this->redirect('misc_guide_to_collecting', array(
            'referral_code' => $organization->getReferralCode(),
        ));
      }
      elseif (OrganizationPeer::ACCESS_MODERATED == $organization->getAccess())
      {
        return $this->redirect('misc_guide_to_collecting', array(
            'r' => $this->getController()->genUrl(array(
                'sf_route' => 'organization_by_slug',
                'sf_subject' => $organization,
             )),
        ));
      }
    }

    return $this->redirect('organization_by_slug', $organization);
  }

}
