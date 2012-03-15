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

  public function executeLogin(sfWebRequest $request)
  {
    // redirect to homepage if already logged in
    if ($this->getUser()->isAuthenticated())
    {
      return $this->redirect('@homepage');
    }

    // Auto login the collector if a hash was provided
    if (( $collector = CollectorPeer::retrieveByHash($request->getParameter('hash')) ))
    {
      $this->getUser()->Authenticate(true, $collector, $remember = false);

      // redirect to last page or homepage after login
      return $this->redirect($request->getParameter('goto', '@homepage'));
    }

    $form = new CollectorLoginForm();
    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
        /* @var $collector Collector */
        $collector = $form->getValue('collector');
        $this->getUser()->Authenticate(
          true,
          $collector,
          $form->getValue('remember')
        );

        $welcomePage = $this->getUser()->getReferer('@homepage');
        return $this->redirect($welcomePage);
      }
    }
    else
    {
      // if we have been forwarded, then the referer is the current URL
      // if not, this is the referer of the current request
      $this->getUser()->setReferer(
        $this->getContext()->getActionStack()->getSize() > 1
          ? $request->getUri()
          : $request->getReferer($request->getParameter('goto'))
      );
    }

    $this->form = $form;
    $this->rpxnow = sfConfig::get('app_credentials_rpxnow');

    return sfView::SUCCESS;
  }

  public function executeRPXTokenLogin(sfWebRequest $request)
  {
    $this->forward404Unless($token = $request->getParameter('token'));

    include_once sfConfig::get('sf_lib_dir') . '/vendor/janrain/engage.auth.lib.php';
    $credentials = sfConfig::get('app_credentials_rpxnow');

    $result = engage_auth_info($credentials['api_key'], $token, ENGAGE_FORMAT_JSON, true);
    $auth_info_array = engage_parse_result($result, ENGAGE_FORMAT_JSON, true);

    if (false !== $result && ENGAGE_STAT_OK === $auth_info_array['stat'])
    {
      $profile = $auth_info_array['profile'];
      $collector = CollectorPeer::retrieveByIdentifier($profile['identifier']);

      if (!$collector)
      {
        $collector = CollectorPeer::createFromRPXProfile($profile);
      }

      if ($collector instanceof Collector)
      {
        $this->getUser()->Authenticate(true, $collector, true);

        return $this->redirect('@homepage');
      }
    }

    $this->getResponse()->addHttpMeta(
      'refresh',
      '5;' . $this->getController()->genUrl('@homepage'));

    return sfView::ERROR;
  }

  public function executeLogout(sfWebRequest $request)
  {
    $this->getUser()->Authenticate(false);
    $this->getUser()->setFlash('success',
      $this->__('You have successfully signed out of your account'));

    $url = $request->getParameter('goto', $this->getRequest()->getReferer());

    /**
     * Handling errors where the $_GET['goto'] is double urlencoded()
     */
    if (substr($url, 0, 13) == 'http%3A%2F%2F')
    {
      $url = urldecode($url);
    }

    return $this->redirect(!empty($url) ? $url : '@homepage');
  }

}
