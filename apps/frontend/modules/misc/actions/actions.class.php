<?php

/**
 * misc actions.
 *
 * @package    CollectorsQuest
 * @subpackage misc
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class miscActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  *
  * @return string
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->signup_form = new CollectorSignupFooterForm();
    $this->login_form  = new CollectorLoginForm();

    return sfView::SUCCESS;
  }
}
