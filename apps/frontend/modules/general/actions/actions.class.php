<?php

/**
 * general actions.
 *
 * @package    CollectorsQuest
 * @subpackage general
 * @author     Collectors
 */
class generalActions extends cqFrontendActions
{

  public function executeIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeCountdown()
  {
    $launch = new DateTime('2012-05-15');
    $now = new DateTime();
    $this->time_left = $launch->diff($now);

    return sfView::SUCCESS;
  }

  public function executeLogin(sfWebRequest $request)
  {
    // redirect to homepage if already logged in
    if ($this->getUser()->isAuthenticated())
    {
      $this->redirect('@homepage');
    }

    // Auto login the collector if a hash was provided
    if (( $collector = CollectorPeer::retrieveByHash($request->getParameter('hash')) ))
    {
      $this->getUser()->Authenticate(true, $collector, $remember = false);

      // redirect to last page or homepage after login
      $this->redirect($request->getParameter('goto', '@homepage'));
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
        $this->redirect($welcomePage);
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

    /** @define "sfConfig::get('sf_lib_dir')" "lib" */
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
        $this->redirect('@homepage');
      }
    }

    // forward the user to the homepage after 5 seconds
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

    $this->redirect(!empty($url) ? $url : '@homepage');
  }

  public function executeRecoverPassword(sfWebRequest $request)
  {
    // redirect to homepage if already logged in
    if ($this->getUser()->isAuthenticated())
    {
      $this->redirect('@homepage');
    }

    $form = new PasswordRecoveryForm();

    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $email = $form->getValue('email');
        $collector = CollectorQuery::create()->findOneByEmail($email);

        $password = IceStatic::getUniquePassword();
        $collector->setPassword($password);
        $collector->save();

        $cqEmail = new cqEmail($this->getMailer());
        $sent = $cqEmail->send('Collector/password_reminder', array(
            'to' => $email,
            'params' => array(
              'collector' => $collector,
              'password' => $password,
            ),
        ));

        if ($sent)
        {
          $this->getUser()->setFlash('success', $this->__(
            'We have sent an email to %email% with your new password.',
            array('%email%' => $email)
          ));

          $this->redirect('@login');
        }
        else
        {
          $this->getUser()->setFlash('error', $this->__(
            'There was a problem sending an email. Please try again a little bit later!'
          ));
        }
      } // valid form
    } // post request

    $this->form = $form;

    return sfView::SUCCESS;
  }

}
