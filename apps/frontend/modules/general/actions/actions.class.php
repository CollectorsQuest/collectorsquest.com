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
    /**
     * Get the latest 2 Blog posts and its first image
     */
    $blog_posts = wpPostPeer::getLatestPosts(2);

    /** @var $blog_post wpPost */
    foreach ($blog_posts as $blog_post)
    {
      if (!$blog_post->getPrimaryImage())
      {
        if ($thumbnail = $blog_post->getPostThumbnail())
        {
          $blog_post->setPrimaryImage(sfConfig::get('sf_web_dir') . $thumbnail);
        }
        else if (preg_match('/<img[^>]+src[\\s=\'"]+([^"\'>\\s]+)/is', $blog_post->getPostContent(), $m))
        {
          if (IceWebBrowser::isUrl($m[1]))
          {
            $blog_post->setPrimaryImage($m[1]);
          }
          else
          {
            $filename = sfConfig::get('sf_web_dir') . '/' . $m[1];
            $blog_post->setPrimaryImage($filename);
          }
        }
      }
    }

    $this->blog_posts = $blog_posts;

    /** @var $q wpPostQuery */
    $q = wpPostQuery::create()
       ->filterByPostType('homepage_showcase')
       ->limit(1);

    // Limit to only published in production
    if (sfConfig::get('sf_environment') === 'prod')
    {
      $q
        ->filterByPostStatus('publish')
        ->addAscendingOrderByColumn('RAND()');
    }
    else
    {
      $q->orderByPostDate(Criteria::DESC);
    }

    /** @var $themes wpPost[] */
    $themes = $q->find();

    foreach ($themes as $theme)
    {
      $values = unserialize($theme->getPostMetaValue('_homepage_showcase_items'));

      // Initialize the arrays
      $collector_ids = $collection_ids = $collectible_ids = $video_ids = array();

      if (!empty($values['cq_collector_ids']))
      {
        $collector_ids = explode(',', $values['cq_collector_ids']);
        $collector_ids = array_map('trim', $collector_ids);
        $collector_ids = array_filter($collector_ids);
      }
      if (!empty($values['cq_collection_ids']))
      {
        $collection_ids = explode(',', $values['cq_collection_ids']);
        $collection_ids = array_map('trim', $collection_ids);
        $collection_ids = array_filter($collection_ids);
      }
      if (!empty($values['cq_collectible_ids']))
      {
        $collectible_ids = explode(',', $values['cq_collectible_ids']);
        $collectible_ids = array_map('trim', $collectible_ids);
        $collectible_ids = array_filter($collectible_ids);
      }
      if (!empty($values['magnify_video_ids']))
      {
        $video_ids = explode(',', $values['magnify_video_ids']);
        $video_ids = array_map('trim', $video_ids);
        $video_ids = array_filter($video_ids);
      }

      if ($collection_ids)
      {
        /**
         * Get 2 Collections
         *
         * @var $q FrontendCollectorCollectionQuery
         */
        $q = FrontendCollectorCollectionQuery::create()
          ->filterById($collection_ids, Criteria::IN)
          ->limit(2)
          ->addAscendingOrderByColumn('FIELD(collector_collection.id, '. implode(',', $collection_ids) .')');

        $this->collections = $q->find();
      }
      if ($collectible_ids)
      {
        if (IceGateKeeper::locked('independence_day'))
        {
          shuffle($collectible_ids);
        }

        /**
         * Get the Collectibles
         *
         * @var $q FrontendCollectibleQuery
         */
        $q = FrontendCollectibleQuery::create()
           ->filterById($collectible_ids, Criteria::IN)
           ->addAscendingOrderByColumn('FIELD(collectible.id, '. implode(',', $collectible_ids) .')');

        IceGateKeeper::open('independence_day') ?
          $q->limit(47) : $q->limit(22);

        $this->collectibles = $q->find();
      }
    }

    return IceGateKeeper::open('independence_day') ?
      'IndependenceDay' : sfView::SUCCESS;
  }

  public function executeDefault()
  {
    return $this->redirect('/', 301);
  }

  public function executeLogin(sfWebRequest $request)
  {
    // Auto login the collector if a hash was provided
    if ($collector = CollectorPeer::retrieveByHash($request->getParameter('hash')))
    {
      $this->getUser()->Authenticate(true, $collector, $remember = false);

      // redirect to last page or homepage after login
      return $this->redirect($request->getParameter('r', '@collector_me'));
    }
    // redirect to homepage if already logged in
    else if ($this->getUser()->isAuthenticated())
    {
      return $this->redirect($request->getParameter('r', '@collector_me'));
    }

    $form = new CollectorLoginForm();

    if ($request->getParameter('module') == 'general' && $request->getParameter('action') == 'login')
    {
      $form->setDefault('goto', $this->generateUrl('collector_me'));
    }

    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
        /* @var $collector Collector */
        $collector = $form->getValue('collector');
        $this->getUser()->Authenticate(true, $collector, $form->getValue('remember'));

        if ($this->getUser()->hasAttribute('preselected_seller_package'))
        {
          $package = $this->getUser()->getAttribute('preselected_seller_package');
          $this->getUser()->getAttributeHolder()->remove('preselected_seller_package');

          return $this->redirect(array(
              'sf_route' =>'seller_packages',
              'package' => $package
          ));
        }

        $goto = $request->getParameter('r', $form->getValue('goto'));
        $goto = !empty($goto) ? $goto : $this->getUser()->getReferer('@collector_me');

        // when JS is disabled or there was a problem with cross-iframe communication
        if (false !== strpos($goto, '_video'))
        {
          $goto = '@collector_me';
        }

        return $this->redirect($goto);
      }
    }
    else
    {
      // if we have been forwarded, then the referer is the current URL
      // if not, this is the referer of the current request
      $this->getUser()->setReferer(
        $this->getContext()->getActionStack()->getSize() > 1
          ? $request->getUri()
          : $request->getParameter('r', $request->getReferer('@collector_me'))
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
      $new_collector = false;
      $profile = $auth_info_array['profile'];
      $collector = CollectorPeer::retrieveByIdentifier($profile['identifier']);

      if (!$collector)
      {
        // Run the pre create hook
        $this->getUser()->preCreateHook();

        $collector = CollectorPeer::createFromRPXProfile($profile);

        // Run the post create hook
        $this->getUser()->postCreateHook($collector);

        $new_collector = true;
      }

      if ($collector instanceof Collector)
      {
        $this->getUser()->Authenticate(true, $collector, true);

        if ($this->getUser()->hasAttribute('preselected_seller_package'))
        {
          $package = $this->getUser()->getAttribute('preselected_seller_package');
          $this->getUser()->getAttributeHolder()->remove('preselected_seller_package');

          return $this->redirect(array(
              'sf_route' =>'seller_packages',
              'package' => $package
          ));
        }
        if ($new_collector)
        {
          return $this->redirect('@mycq_profile');
        }
        else
        {
          return $this->redirect(IceRequestHistory::getCurrentUri() ?: '@collector_me');
        }
      }
    }

    // forward the user to the homepage after 5 seconds
    $this->getResponse()->addHttpMeta(
      'refresh',
      '5;' . $this->getController()->genUrl('@homepage')
    );

    return sfView::ERROR;
  }

  public function executeLogout(sfWebRequest $request)
  {
    $this->getUser()->Authenticate(false);
    $this->getUser()->setFlash('success',
      $this->__('You have successfully signed out of your account'));

    $url = $request->getParameter('r', $request->getReferer() ?: '@homepage');

    /**
     * Handling errors where the $_GET['r'] is double urlencoded()
     */
    if (substr($url, 0, 13) == 'http%3A%2F%2F')
    {
      $url = urldecode($url);
    }

    // when JS is disabled or there was a problem with cross-iframe communication
    if (false !== strpos($url, '_video'))
    {
      $url = '@homepage';
    }

    return $this->redirect($url);
  }

  public function executeRecoverPassword(sfWebRequest $request)
  {
    // redirect to homepage if already logged in
    if ($this->getUser()->isAuthenticated())
    {
      return $this->redirect('@homepage');
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

          return $this->redirect('@login');
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

  public function executeComingSoon()
  {
    // Building the breadcrumbs and page title
    $this->addBreadcrumb($this->__('Coming Soon'));
    $this->prependTitle($this->__('Coming Soon'));

    return sfView::SUCCESS;
  }

  public function executeError404()
  {
    return cqStatic::isCrawler() ?
      sfView::HEADER_ONLY : sfView::SUCCESS;
  }

  public function executeError50x()
  {
    return cqStatic::isCrawler() ?
      sfView::HEADER_ONLY : sfView::SUCCESS;
  }

}
