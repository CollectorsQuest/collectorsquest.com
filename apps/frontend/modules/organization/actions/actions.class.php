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

}
