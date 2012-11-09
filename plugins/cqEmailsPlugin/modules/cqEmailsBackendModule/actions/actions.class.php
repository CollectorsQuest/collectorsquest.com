<?php

require_once dirname(__FILE__) . '/../lib/cqEmailsBackendModuleGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/cqEmailsBackendModuleGeneratorHelper.class.php';

/**
 * cqEmailsBackendModule actions.
 *
 * @package    cqEmailsPlugin
 * @subpackage cqEmailsBackendModule
 * @author     Pavel Goncharov
 */
class cqEmailsBackendModuleActions extends autoCqEmailsBackendModuleActions
{
  public function executeListShowHtml(sfWebRequest $request)
  {
    /* @var $EmailsLog EmailsLog */
    $this->EmailsLog = $this->getRoute()->getObject();

    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    return sfView::SUCCESS;
  }

  public function executeListShowPlain(sfWebRequest $request)
  {
    /* @var $EmailsLog EmailsLog */
    $this->EmailsLog = $this->getRoute()->getObject();

    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    return sfView::SUCCESS;
  }

  public function executeListShowHeaders(sfWebRequest $request)
  {
    /* @var $EmailsLog EmailsLog */
    $this->EmailsLog = $this->getRoute()->getObject();

    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    return sfView::SUCCESS;  }
}
