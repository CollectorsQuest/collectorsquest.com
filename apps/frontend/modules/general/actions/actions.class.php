<?php

/**
 * general actions.
 *
 * @package    CollectorsQuest
 * @subpackage general
 * @author     Collectors
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class generalActions extends cqFrontendActions
{

  public function executeIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeCountdown()
  {
    return sfView::SUCCESS;
  }

  public function executeLogout(sfWebRequest $request)
  {
    $this->getUser()->Authenticate(false);
    $url = $request->getParameter('r', $this->getRequest()->getReferer());

    /**
     * Handling errors where the $_GET['r'] is double urlencoded()
     */
    if (substr($url. 0, 13) == 'http%3A%2F%2F')
    {
      $url = urldecode($url);
    }

    $this->getUser()->setFlash('success', $this->__('You have successfully signed out of your account'));

    return $this->redirect(!empty($url) ? $url : '@homepage');
  }

}
