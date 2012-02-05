<?php

/**
 * general actions.
 *
 * @package    CollectorsQuest
 * @subpackage general
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class generalActions extends cqActions
{

  public function executeIndex()
  {
    // Get the latest Blog post and its first image
    $blog_posts = wpPostPeer::getLatestPosts(6);

    /** @var $blog_post wpPost */
    $blog_post = array_shift($blog_posts);

    $pattern = '/<img[^>]+src[\\s=\'"]+([^"\'>\\s]+)/is';
    $blog_image = null;

    $q = new MultimediaQuery();
    $q->filterByModel('wpPost')
      ->filterByModelId($blog_post->getId())
      ->filterByType('image')
      ->filterByIsPrimary(true);

    if (!$blog_image = $q->findOne())
    {
      // Find the first image in the article
      preg_match($pattern, $blog_post->getPostContent(), $match);

      if (isset($match[1]))
      {
        if (IceWebBrowser::isUrl($match[1]))
        {
          $blog_image = MultimediaPeer::createMultimediaFromUrl($blog_post, $match[1]);
        }
        else
        {
          $filename = sfConfig::get('sf_web_dir') . '/' . $match[1];
          $blog_image = MultimediaPeer::createMultimediaFromFile($blog_post, $filename);
        }

        if ($blog_image instanceof Multimedia)
        {
          $blog_image->setIsPrimary(true);
          $blog_image->makeThumb('300x225', 'shave');
          $blog_image->save();
        }
      }
    }

    $this->blog_post = $blog_post;
    $this->blog_content = strip_tags(str_replace(
      array('[/caption]', '[caption', ']'), array('</caption>', '<caption', '>'), $blog_post->getPostContent()
    ));
    $this->blog_image = $blog_image;
    $this->blog_posts = $blog_posts;

    $c = new Criteria();
    $c->addDescendingOrderByColumn(CollectionPeer::CREATED_AT);
    $c->setLimit(5);
    $this->latest_collections = CollectionPeer::doSelect($c);

    // Video
    $c = new Criteria();
    $c->add(VideoPeer::IS_PUBLISHED, true);
    $c->addJoin(VideoPeer::ID, VideoPlaylistPeer::VIDEO_ID, Criteria::RIGHT_JOIN);
    $c->add(VideoPlaylistPeer::PLAYLIST_ID, array(1, 4), Criteria::IN);
    $c->addDescendingOrderByColumn('RAND()');
    $this->featured_video = VideoPeer::doSelectOne($c);

    $c->clear();
    $c->add(VideoPeer::IS_PUBLISHED, true);
    $c->addDescendingOrderByColumn(VideoPeer::PUBLISHED_AT);
    $c->setLimit(5);
    $this->latest_videos = VideoPeer::doSelect($c);

    // Featured Week
    if (!$this->featured_week = FeaturedPeer::getCurrentFeatured(FeaturedPeer::TYPE_FEATURED_WEEK))
    {
      $this->featured_week = FeaturedPeer::getLatestFeatured(FeaturedPeer::TYPE_FEATURED_WEEK);
    }
    if ($this->featured_week instanceof Featured)
    {
      $this->featured_collectible = $this->featured_week->getHomepageCollectible();
    }

    // We want to show 100 tags on the bottom of the homepage, mainly for SEO
    $this->collection_tags = CollectionPeer::getPopularTags(100);
    uksort($this->collection_tags, "strcasecmp");

    return sfView::SUCCESS;
  }

  public function executeLogin(sfWebRequest $request)
  {
    // Try to auto-login the collector if a hash was provided
    if ($collector = CollectorPeer::retrieveByHash($request->getParameter('hash')))
    {
      $this->getUser()->Authenticate(true, $collector, true);

      // redirect to last page
      return $this->redirect($request->getParameter('goto', '@community'));
    }
    else if ($email = $this->getRequestParameter('email'))
    {
      $c = new Criteria();
      $c->add(CollectorPeer::EMAIL, $email);
      $collector = CollectorPeer::doSelectOne($c);

      if ($collector)
      {
        $password = IceStatic::getUniquePassword();
        $collector->setPassword($password);
        $collector->save();

        $to = $collector->getEmail();
        $subject = $this->__('Your new password for CollectorsQuest.com');
        $body = $this->getPartial(
          'emails/collector_password_reminder',
          array('collector' => $collector, 'password' => $password)
        );

        if ($this->sendEmail($to, $subject, $body))
        {
          $this->getUser()->setFlash(
            'success', $this->__('We have sent an email to %email% with your new password.', array('%email%' => $to))
          );
        }
        else
        {
          $this->getUser()->setFlash(
            'error', $this->__('There was a problem sending an email. Please try again a little bit later!')
          );
        }
      }
      else
      {
        $this->getUser()->setFlash(
          'error', 'We could not find this email address as a valid collector!'
        );
      }
    }

    // Create the login form
    $form = new CollectorLoginForm();

    if ($request->isMethod('post'))
    {
      $form->bind(array(
        'username' => $request->getParameter('username'),
        'password' => $request->getParameter('password'),
      ));

      // handle the form submission
      $username = $this->getRequestParameter('username');
      $password = $this->getRequestParameter('password');

      $c = new Criteria();
      $c->add(CollectorPeer::USERNAME, $username);
      $collector = CollectorPeer::doSelectOne($c);

      // collector exists?
      if ($collector)
      {
        // password is OK?
        if (sha1($collector->getSalt() . $password) == $collector->getSha1Password())
        {
          $this->getUser()->Authenticate(true, $collector, $this->getRequestParameter('remember'));

          $welcomePage = $request->getReferer();
          if (!$welcomePage || $welcomePage == $request->getUri())
          {
            $welcomePage = $request->getReferer(
              $this->getUser()->isAuthenticated() && $this->getUser()->hasCredential('seller') ? '@marketplace' : '@community'
            );
          }

          $goto = $this->getUser()->getAttribute('return_url', $request->getParameter('goto', $welcomePage), 'system');
          $this->getUser()->setAttribute('return_url', null, 'system');

          // redirect to last page
          $this->redirect(!empty($goto) ? $goto : '@homepage');
        }
      }

      $this->getUser()->setFlash("error", sprintf(
        '%s <a href="#reminder">%s</a>',
        $this->__("This username/password combination is not valid."),
        $this->__("Forgot your username or password?")
      ));
    }

    $this->rpxnow = sfConfig::get('app_credentials_rpxnow');
    $this->form = $form;

    $url = $this->getUser()->getAttribute('return_url', $request->getParameter('goto'), 'system');
    $this->getUser()->setAttribute('return_url', $url, 'system');

    // Building the breadcrumbs and page title
    $this->addBreadcrumb($this->__('Sign in to Your Account'));
    $this->prependTitle($this->__('Sign in to Your Account'));

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

    return $this->redirect(!empty($url) ? $url : '@community');
  }

  public function executePassword()
  {
    $this->redirectIf($this->getUser()->isAuthenticated(), "@community");

    return sfView::SUCCESS;
  }

  public function executeRPXToken(sfWebRequest $request)
  {
    $token = $request->getParameter('token');
    $this->forward404Unless($token);

    include_once sfConfig::get('sf_lib_dir') . '/vendor/janrain/engage.auth.lib.php';
    $credentials = sfConfig::get('app_credentials_rpxnow');

    $result = engage_auth_info($credentials['api_key'], $token, ENGAGE_FORMAT_JSON, true);
    $auth_info_array = engage_parse_result($result, ENGAGE_FORMAT_JSON, true);

    if ($result !== false && $auth_info_array['stat'] === 'ok')
    {
      $profile = $auth_info_array['profile'];

      $c = new Criteria();
      $c->addJoin(CollectorPeer::ID, CollectorIdentifierPeer::COLLECTOR_ID);
      $c->add(CollectorIdentifierPeer::IDENTIFIER, $profile['identifier']);

      if (!$collector = CollectorPeer::doSelectOne($c))
      {
        $collector = CollectorPeer::createFromRPXProfile($profile);
      }

      if ($collector instanceof Collector)
      {
        $this->getUser()->Authenticate(true, $collector, true);

        return $this->redirect('@collector_me');
      }
    }

    return sfView::ERROR;
  }

  public function executeFeedback(sfWebRequest $request)
  {
    $this->form = new FeedbackForm();
    $this->form->setDefault('page', $request->getParameter('page', $request->getReferer()));

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('feedback'));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();

        $body = $this->getPartial('emails/feedback', array(
          'fullname' => $values['fullname'],
          'email' => $values['email'],
          'message' => nl2br($values['message']),
          'page' => urldecode($values['page']),

          "f_ip_address" => cqStatic::getUserIpAddress(),
          "f_javascript_enabled" => $values['f_javascript_enabled'],
          "f_browser_type" => $values['f_browser_type'],
          "f_browser_version" => $values['f_browser_version'],
          "f_browser_color_depth" => $values['f_browser_color_depth'],
          "f_resolution" => $values['f_resolution'],
          "f_browser_size" => $values['f_browser_size']
        ));

        try
        {
          $bc = cqStatic::getBasecampClient();
          $response = $bc->createTodoItemForList(17144329, 'Feedback from '. $values['fullname'], 'person', 8866041, false);
          $bc->createCommentForTodoItem($response['id'], $body);
        }
        catch (Exception $e)
        {
          $this->getMailer()->composeAndSend('no-reply@collectorsquest.com', 'info@collectorsquest.com', '[Website Feedback] '. $values['fullname'], $body);
        }

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
