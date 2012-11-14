<?php

require_once dirname(__FILE__).'/../lib/organizationGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/organizationGeneratorHelper.class.php';

/**
 * organization actions.
 *
 * @package    CollectorsQuest
 * @subpackage organization
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class organizationActions extends autoOrganizationActions
{
  public function executeListGoToFrontendPage()
  {
    /* @var $organization Organization */
    $organization = $this->getRoute()->getObject();

    $url = sfProjectConfiguration::getActive()->generateFrontendUrl(
      'organization_by_slug',
      $organization
    );

    $this->redirect($url);
  }
}
