<?php

require_once dirname(__FILE__) . '/../lib/sent_emailGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/sent_emailGeneratorHelper.class.php';

/**
 * sent_email actions.
 *
 * @package    CollectorsQuest
 * @subpackage sent_email
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sent_emailActions extends autoSent_emailActions
{
  public function executeListShowHtml(sfWebRequest $request)
  {
    /* @var $sent_email SentEmail */
    $this->sent_email = $this->getRoute()->getObject();

    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    return sfView::SUCCESS;
  }

  public function executeListShowPlain(sfWebRequest $request)
  {
    /* @var $sent_email SentEmail */
    $this->sent_email = $this->getRoute()->getObject();

    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    return sfView::SUCCESS;
  }

  public function executeListShowHeaders(sfWebRequest $request)
  {
    /* @var $sent_email SentEmail */
    $this->sent_email = $this->getRoute()->getObject();

    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    return sfView::SUCCESS;
  }
}
