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
   */
  public function executeIndex()
  {
    $this->redirect('@homepage');
  }

  /**
   * @return string
   */
  public function executeGuideToCollecting()
  {
    $signupForm = new CollectorGuideSignupForm();
    $loginForm  = new CollectorGuideLoginForm();
    $display = 'signup';

    $request = $this->getRequest();
//    dd($request->getParameterHolder()->getAll(), $request->getMethod());
    if (sfRequest::POST == $request->getMethod())
    {
      if ($request->getParameter($signupForm->getName()))
      {
        $signupForm->bind($request->getParameter($signupForm->getName()));
      }
      else if ($request->getParameter($loginForm->getName()))
      {
        $display = 'login';
        $loginForm->bind($request->getParameter($loginForm->getName()));

        if ($loginForm->isValid())
        {
          /* @var $collector Collector */
          $collector = $loginForm->getValue('collector');
          $this->getUser()->Authenticate(true, $collector, $loginForm->getValue('remember'));

          $this->redirect('@misc_guide_download');
        }
      }
    }

    $this->signup_form = $signupForm;
    $this->login_form = $loginForm;
    $request->setParameter('display', $display);

    return sfView::SUCCESS;
  }

  /**
   * Action GuideDownload
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeGuideDownload(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

}
