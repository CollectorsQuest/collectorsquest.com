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

    try
    {
      OrganizationAccess::createMembershipRequest($organization, $this->getCollector());

      $this->getUser()->setFlash('success', 'Membership requested');
    }
    catch (OrganizationAccessMembershipRequestAlreadyPendingException $e)
    {
      $this->getUser()->setFlash('error', 'You already requested membership for this organization');
    }
    catch (OrganizationAccessMembershipRequestAlreadyApprovedException $e)
    {
      $this->getUser()->setFlash('error', 'You already requested membership for this organization');
    }
    catch (OrganizationAccessMembershipRequestAlreadyDeniedException $e)
    {
      $this->getUser()->setFlash('error', 'You already requested membership for this organization');
    }
    catch (OrganizationAccessMembershipRequestAlreadyMemberException $e)
    {
      $this->getUser()->setFlash('error', 'You are already a member of this organization');
    }

    $this->redirect('organization_by_slug', $organization);
  }

}
