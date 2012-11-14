<?php

/**
 * organization components.
 *
 * @package    CollectorsQuest
 * @subpackage organization
 * @author     Collectors Quest, Inc.
 */
class organizationComponents extends cqFrontendComponents
{

  public function executeSidebarIndex()
  {
    $organization = OrganizationPeer::retrieveByPk($this->getRequestParameter('id'));

    if (!$organization)
    {
      return sfView::NONE;
    }

    $this->organization = $organization;
  }

  public function executeWidgetMembers()
  {
    $this->members = $this->organization->getCollectors(CollectorQuery::create()
      ->limit(20)
    );
  }

}
