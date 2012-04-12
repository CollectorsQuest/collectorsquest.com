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
    // Get the latest Blog post and its first image
    $this->blog_posts = wpPostPeer::getLatestPosts(2);

    $q = wpPostQuery::create()
       ->filterByPostType('homepage_showcase')
       ->filterByPostStatus('publish')
       ->orderByPostDate(Criteria::DESC)
       ->limit(1);

    /** @var $themes wpPost[] */
    $themes = $q->find();

    foreach ($themes as $theme)
    {
      $collector_ids = explode(',', $theme->getPostMetaValue('cq_collector_ids'));
      $collection_ids = explode(',', $theme->getPostMetaValue('cq_collection_ids'));
      $collectible_ids = explode(',', $theme->getPostMetaValue('cq_collectible_ids'));
      $video_ids = explode(',', $theme->getPostMetaValue('cq_video_ids'));

      if ($collection_ids)
      {
        // Get 2 Collections
        $q = CollectionQuery::create()
          ->filterById($collection_ids, Criteria::IN)
          ->limit(2)
          ->addAscendingOrderByColumn('FIELD(id, '. implode(',', $collection_ids) .')');

        $this->collections = $q->find();
      }
      if ($collectible_ids)
      {
        // Get 22 Collectibles
        $q = CollectibleQuery::create()
           ->filterById($collectible_ids, Criteria::IN)
           ->limit(22)
           ->addAscendingOrderByColumn('FIELD(id, '. implode(',', $collectible_ids) .')');

        $this->collectibles = $q->find();
      }
    }

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
      $this->redirect($request->getParameter('goto', '@collector_me'));
    }

    // Auto login the collector if a hash was provided
    if ($collector = CollectorPeer::retrieveByHash($request->getParameter('hash')))
    {
      $this->getUser()->Authenticate(true, $collector, $remember = false);

      // redirect to last page or homepage after login
      $this->redirect($request->getParameter('goto', '@collector_me'));
    }

    $form = new CollectorLoginForm();
    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
        /* @var $collector Collector */
        $collector = $form->getValue('collector');
        $this->getUser()->Authenticate(true, $collector, $form->getValue('remember'));

        $goto = $request->getParameter('goto', $form->getValue('goto'));
        $this->redirect(!empty($goto) ? $goto : $this->getUser()->getReferer('@collector_me'));
      }
    }
    else
    {
      // if we have been forwarded, then the referer is the current URL
      // if not, this is the referer of the current request
      $this->getUser()->setReferer(
        $this->getContext()->getActionStack()->getSize() > 1
          ? $request->getUri()
          : $request->getParameter('goto', $request->getReferer('@collector_me'))
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

  public function executeFeedback(sfWebRequest $request)
  {
    $this->form = new FeedbackForm();
    $this->form->setDefault('page', $request->getParameter('page', $request->getReferer()));

    if ($request->isMethod('post'))
    {
      $sent = false;

      $this->form->bind($request->getParameter('feedback'));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();

        dd(array(
        "f_ip_address" => cqStatic::getUserIpAddress(),
        "f_javascript_enabled" => $values['f_javascript_enabled'],
        "f_browser_type" => $values['f_browser_type'],
        "f_browser_version" => $values['f_browser_version'],
        "f_browser_color_depth" => $values['f_browser_color_depth'],
        "f_resolution" => $values['f_resolution'],
        "f_browser_size" => $values['f_browser_size']
      ));

        $cqEmail = new cqEmail($this->getMailer());
        $sent = $cqEmail->send('internal/feedback', array(
          'to' => 'info@collectorsquest.com',
          'subject' => '[Feedback] '. $values['fullname'],
          'params' => array(
            'feedback' => array(
              'fullname' => $values['fullname'],
              'email' => $values['email'],
              'message' => nl2br($values['message']),
              'page' => urldecode($values['page'])
            ),
            'browser' => array(
              "f_ip_address" => cqStatic::getUserIpAddress(),
              "f_javascript_enabled" => $values['f_javascript_enabled'],
              "f_browser_type" => $values['f_browser_type'],
              "f_browser_version" => $values['f_browser_version'],
              "f_browser_color_depth" => $values['f_browser_color_depth'],
              "f_resolution" => $values['f_resolution'],
              "f_browser_size" => $values['f_browser_size']
            ),
          ),
        ));


      }

      if ($sent)
      {
        $this->getUser()->setFlash('success', $this->__('Thank you for the feedback. If needed, we will get in touch with you within the next business day.', array(), 'flash'));
      }
      else
      {
        $this->getUser()->setFlash('error', $this->__('There are errors in the fields or some are left empty.', array(), 'flash'));
      }
    }

    return sfView::SUCCESS;
  }

  public function executeComingSoon()
  {
    // Building the breadcrumbs and page title
    $this->addBreadcrumb($this->__('Coming Soon'));
    $this->prependTitle($this->__('Coming Soon'));

    return sfView::SUCCESS;
  }


  public function executeError404()
  {
    // Building the breadcrumbs and page title
    $this->addBreadcrumb($this->__('Page Not Found'));
    $this->prependTitle($this->__('Page Not Found'));

    return sfView::SUCCESS;
  }

  public function executeError50x()
  {
    // Building the breadcrumbs and page title
    $this->addBreadcrumb($this->__('Unexpected Error'));
    $this->prependTitle($this->__('Unexpected Error'));

    return sfView::SUCCESS;
  }

}
