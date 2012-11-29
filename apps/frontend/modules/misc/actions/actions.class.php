<?php

/**
 * misc actions.
 *
 * @package    CollectorsQuest
 * @subpackage misc
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class miscActions extends cqFrontendActions
{

  /**
   * Executes index action
   */
  public function executeIndex()
  {
    $this->redirect('@homepage');
  }

  public function executeGuideToCollectingShortcut()
  {
    $this->redirect('@misc_guide_to_collecting');
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeGuideToCollecting(sfWebRequest $request)
  {
    if ($this->getUser()->isAuthenticated())
    {
      $this->redirect('@misc_guide_download');
    }

    $signupForm = new CollectorGuideSignupForm();
    $loginForm = new CollectorGuideLoginForm();
    $display = 'signup';

    if (sfRequest::POST == $request->getMethod())
    {
      if ($request->getParameter($signupForm->getName()))
      {
        $signupForm->bind($request->getParameter($signupForm->getName()));

        if ($signupForm->isValid())
        {
          $values = $signupForm->getValues();

          // try to guess the collector's country based on IP address
          $values['country_iso3166'] = cqStatic::getGeoIpCountryCode(
            $request->getRemoteAddress(), $check_against_geo_country = true
          );

          // Run the pre create hook
          $this->getUser()->preCreateHook();

          // create the collector
          $collector = CollectorPeer::createFromArray($values);

          $collector_email = CollectorEmailPeer::retrieveByCollectorEmail(
            $collector, $collector->getEmail()
          );

          // Run the post create hook
          $cqEmail = new cqEmail($this->getMailer());
          $cqEmail->send('misc/validate_email_to_download', array(
            'to' => $collector->getEmail(),
            'subject' => 'Quest Your Best: The Essential Guide to Collecting',
            'params' => array(
              'collector' => $collector,
              'collector_email' => $collector_email,
            ),
          ));

          // authenticate the collector and redirect to @misc_guide_download
          $this->getUser()->Authenticate(true, $collector, false);

          // Run the post create hook (not sending the welcome email yet)
          $this->getUser()->postCreateHook($collector, false);

          $this->getUser()->setFlash(
            'success',
            'Thanks for joining the fine folks at Collectors Quest!
             Please explore some of the sections below.'
          );

          $this->redirect('@mycq');
        }
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
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeGuideDownload(sfWebRequest $request)
  {
    $this->redirectUnless($this->getUser()->isAuthenticated(), '@misc_guide_to_collecting');

    /* @var $collector Collector */
    $collector = $this->getUser()->getCollector();

    $email = CollectorEmailQuery::create()
        ->filterByCollector($collector)
        ->findOneByEmail($collector->getEmail());

    if (!$email || !$email->getIsVerified())
    {
      $form = new CollectorValidateEmailForm($email);

      if (sfRequest::POST == $this->getRequest()->getMethod())
      {
        //Form submited
        $form->bind($this->getRequestParameter($form->getName()));

        if ($form->isValid())
        {
          $cqEmail = new cqEmail($this->getMailer());
          $cqEmail->send('misc/validate_email_to_download', array(
            'to'      => $collector->getEmail(),
            'subject' => 'Quest Your Best: The Essential Guide to Collecting',
            'params'  => array(
              'collector'       => $collector,
              'collector_email' => $email,
            )
          ));

          $this->getUser()->setFlash(
            'success', sprintf('Validation email sent to %s', $email->getEmail())
          );

          $this->redirect('@misc_guide_download');
          return sfView::SUCCESS;
        }
      }

      $this->form = $form;

      return sfView::ALERT;
    }

    $hash = $this->getUser()->getAttribute('hash', false, 'guide');
    $expireAt = $this->getUser()->getAttribute('expire', 0, 'guide');

    if ($this->getRequestParameter('sf_format', false) && $hash && $expireAt >= time())
    {
      $format = $this->getRequestParameter('sf_format');
      $filename = sprintf('Essential Guide to Collecting - CollectorsQuest.%s', $format);
      $mimeType = 'pdf' == $format ? 'application/pdf' : 'application/zip';
      $sourceFile = sfConfig::get('sf_data_dir') . '/download/essential-guide.' . $format;

      header('Expires: 0');
      header('Cache-control: private');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Content-Description: File Transfer');
      header(sprintf('Content-Type: %s, charset=UTF-8; encoding=UTF-8', $mimeType));
      header('Content-disposition: attachment; filename="' . $filename .'"');

      readfile($sourceFile);
      exit(0);
    }

    if (!$hash || $expireAt < time())
    {
      //Generate hash for next 5 minutes
      $hash = ShoppingOrderPeer::getUuidFromId(date('mdHis'));
      $expireAt = strtotime('+5 minute');
      $this->getUser()->setAttribute('hash', $hash, 'guide');
      $this->getUser()->setAttribute('expire', $expireAt, 'guide');
    }

    $this->hash = $hash;

    $this->getResponse()->addOpenGraphMeta('type', 'website');
    $this->getResponse()->addOpenGraphMeta('url', 'http://www.collectorsquest.com/guide');
    $this->getResponse()->addOpenGraphMeta('title',
      'Quest Your Best: The Essential Guide to Collecting | Collectors Quest'
    );
    $this->getResponse()->addOpenGraphMeta('image',
      'http://www.collectorsquest.com/images/frontend/misc/guide-splash-page-img.png'
    );
    $this->getResponse()->addOpenGraphMeta('description',
      "The guide has something for every collector, whether you're just
       beginning to acquire treasures or you're a dedicated hunter looking for the next,
       perfect addition to your display case."
    );

    $this->redirectIf(
      $request->getRequestFormat() && $request->getRequestFormat() !== 'html',
      '@misc_guide_to_collecting'
    );

    return sfView::SUCCESS;
  }

  /**
   * Action GuideVerifyEmail
   *
   * @return string
   */
  public function executeGuideVerifyEmail()
  {
    /* @var $collectorEmail CollectorEmail */
    $collectorEmail = $this->getRoute()->getObject();
    $this->forward404Unless($collectorEmail instanceof CollectorEmail);

    $collector = $collectorEmail->getCollector();
    $collector->setEmail($collectorEmail->getEmail());
    $collector->save();

    $collectorEmail->setIsVerified(true);
    $collectorEmail->save();

    // Finally, send the welcome email
    $cqEmail = new cqEmail($this->getMailer());
    $cqEmail->send($collector->getUserType() . '/welcome_to_cq', array(
      'to' => $collector->getEmail(),
    ));

    $this->getUser()->Authenticate(true, $collector, false);

    $this->redirect('@misc_guide_download');
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeWordPressFeaturedItems(sfWebRequest $request)
  {
    /** @var $wp_post wpPost */
    $wp_post = $this->getRoute()->getObject();

    /** @var $post_id integer */
    $post_id = $request->getParameter('id');

    $values = $wp_post->getPostMetaValue('_featured_items');

    // Initialize the arrays
    $collection_ids = $collectible_ids = $category_ids = $tags = $homepage_collectible_ids = array();
    $collection_ids_exclude = $collectible_ids_exclude = $category_ids_exclude = $tags_exclude = array();
    $collectibles_2x2 = $collectibles_2x1 = $collectibles_1x2 = array();

    // set the number of items on page
    $limit = !empty($values['cq_items_per_page']) ? (int) $values['cq_items_per_page'] : '20';

    // is infinite scroll enabled?
    $this->infinite_scroll = !empty($values['cq_infinite_scroll']) ? (boolean) $values['cq_infinite_scroll'] : false;

    // do we exclude the sidebar and have full page width image?
    $this->no_sidebar = !empty($values['cq_no_sidebar']) ? (boolean) $values['cq_no_sidebar'] : false;

    // set template with no sidebar and full page width image
    if ($this->no_sidebar === true)
    {
      $this->setTemplate('wordPressFeaturedItemsNoSidebar');
    }

    // what is the layout for the page - grid or pinterest
    $this->cq_layout = !empty($values['cq_layout']) ? $values['cq_layout'] : 'grid';
    if ($this->cq_layout != 'grid' && $this->cq_layout != 'pinterest')
    {
      $this->cq_layout = 'grid';
    }

    if (!empty($values['cq_collection_ids']))
    {
      $collection_ids = cqFunctions::explode(',', $values['cq_collection_ids']);
    }
    if (!empty($values['cq_collectible_ids']))
    {
      $collectible_ids = cqFunctions::explode(',', $values['cq_collectible_ids']);

      $parsed_collectible_ids = array();
      foreach ($collectible_ids as $collectible_id)
      {
        if (strstr($collectible_id, ':'))
        {
          $parsed_value = explode(':', $collectible_id);
          $parsed_collectible_ids[] = $parsed_value[0];
          switch ($parsed_value[1]) {
            case '2x2':
              $collectibles_2x2[] = $parsed_value[0];
              break;
            case '2x1':
              $collectibles_2x1[] = $parsed_value[0];
              break;
            case '1x2':
              $collectibles_1x2[] = $parsed_value[0];
              break;
          }
        }
        else
        {
          $parsed_collectible_ids[] = $collectible_id;
        }
      }

      $collectible_ids = $parsed_collectible_ids;
    }
    if (!empty($values['cq_category_ids']))
    {
      $category_ids = cqFunctions::explode(',', $values['cq_category_ids']);
    }
    if (!empty($values['cq_tags']))
    {
      $tags = cqFunctions::explode(',', $values['cq_tags']);
    }
    if (!empty($values['cq_homepage_collectible_ids']))
    {
      $homepage_collectible_ids = cqFunctions::explode(',', $values['cq_homepage_collectible_ids']);

      $parsed_collectible_ids = array();
      foreach ($homepage_collectible_ids as $collectible_id)
      {
        if (strstr($collectible_id, ':'))
        {
          $parsed_value = explode(':', $collectible_id);
          $parsed_collectible_ids[] = $parsed_value[0];
          switch ($parsed_value[1]) {
            case '2x2':
              $collectibles_2x2[] = $parsed_value[0];
              break;
            case '2x1':
              $collectibles_2x1[] = $parsed_value[0];
              break;
            case '1x2':
              $collectibles_1x2[] = $parsed_value[0];
              break;
          }
        }
        else
        {
          $parsed_collectible_ids[] = $collectible_id;
        }
      }

      $homepage_collectible_ids = $parsed_collectible_ids;
      $collectible_ids = array_merge($homepage_collectible_ids, $collectible_ids);
    }

    // exclude values
    if (!empty($values['cq_collection_ids_exclude']))
    {
      $collection_ids_exclude = cqFunctions::explode(',', $values['cq_collection_ids_exclude']);
    }
    if (!empty($values['cq_collectible_ids_exclude']))
    {
      $collectible_ids_exclude = cqFunctions::explode(',', $values['cq_collectible_ids_exclude']);
    }
    if (!empty($values['cq_category_ids_exclude']))
    {
      $category_ids_exclude = cqFunctions::explode(',', $values['cq_category_ids_exclude']);
    }
    if (!empty($values['cq_tags_exclude']))
    {
      $tags_exclude = cqFunctions::explode(',', $values['cq_tags_exclude']);
    }

    $status = $wp_post->getPostStatus();
    $_collectible_ids = $this->getUser()->getAttribute('featured_items_collectible_ids_' . $post_id, null, 'cache');

    /*
     * calculate collectible_ids if collectible_ids are not yet known OR
     * wp post is NOT published OR cache is disabled
     */
    if (!$_collectible_ids || $status !== 'publish' || sfConfig::get('sf_cache', 'false'))
    {
      // add Collectibles based on Category IDs
      if (!empty($category_ids))
      {
        /** @var $q ContentCategoryQuery */
        $q = ContentCategoryQuery::create()
          ->filterById($category_ids, Criteria::IN);

        /** @var $content_categories ContentCategory[] */
        $_content_categories = $q->find();

        /** @var $q FrontendCollectionCollectibleQuery */
        $q = FrontendCollectionCollectibleQuery::create()
          ->filterByContentCategoryWithDescendants($_content_categories)
          ->select('CollectibleId');

        $_collectible_ids_content_categories = $q->find()->toArray();

        $collectible_ids = array_merge($collectible_ids, $_collectible_ids_content_categories);
        $collectible_ids = array_unique($collectible_ids);
      }

      // exclude Collectibles based on Category IDs
      if (!empty($category_ids_exclude))
      {
        /** @var $q ContentCategoryQuery */
        $q = ContentCategoryQuery::create()
          ->filterById($category_ids_exclude, Criteria::IN);

        /** @var $content_categories_exclude ContentCategory[] */
        $_content_categories_exclude = $q->find();

        /** @var $q FrontendCollectionCollectibleQuery */
        $q = FrontendCollectionCollectibleQuery::create()
          ->filterByContentCategoryWithDescendants($_content_categories_exclude)
          ->filterByCollectibleId($homepage_collectible_ids, Criteria::NOT_IN)
          ->select('CollectibleId');

        $_collectible_ids_content_categories_exclude = $q->find()->toArray();

        $collectible_ids_exclude = array_merge($collectible_ids_exclude, $_collectible_ids_content_categories_exclude);
        $collectible_ids_exclude = array_unique($collectible_ids_exclude);
      }

      // add Collections and Collectibles based on tag matching
      if (!empty($tags))
      {
        /** @var $q FrontendCollectorCollectionQuery */
        $q = FrontendCollectorCollectionQuery::create()
          ->filterByTags($tags)
          ->select('Id');

        $_collection_ids_tags = $q->find()->toArray();

        $collection_ids = array_merge($collection_ids, $_collection_ids_tags);
        $collection_ids = array_unique($collection_ids);

        // @todo not sure if/how this sould be done with CollectorCollectionQuery
        /** @var $q FrontendCollectibleQuery */
        $q = FrontendCollectibleQuery::create()
          ->filterByTags($tags)
          ->select('Id');

        $_collectible_ids_tags = $q->find()->toArray();

        $collectible_ids = array_merge($collectible_ids, $_collectible_ids_tags);
        $collectible_ids = array_unique($collectible_ids);
      }

      // exclude Collections and Collectibles based on tag matching
      if (!empty($tags_exclude))
      {
        /** @var $q FrontendCollectorCollectionQuery */
        $q = FrontendCollectorCollectionQuery::create()
          ->filterByTags($tags_exclude)
          ->select('Id');

        $_collection_ids_tags_exclude = $q->find()->toArray();

        $collection_ids_exclude = array_merge($collection_ids_exclude, $_collection_ids_tags_exclude);
        $collection_ids_exclude = array_unique($collection_ids_exclude);

        /** @var $q CollectibleQuery */
        $q = CollectibleQuery::create()
          ->isComplete()
          ->isPartOfCollection()
          ->filterByTags($tags_exclude)
          ->filterById($homepage_collectible_ids, Criteria::NOT_IN)
          ->select('Id');

        $_collectible_ids_tags_exclude = $q->find()->toArray();

        $collectible_ids_exclude = array_merge($collectible_ids_exclude, $_collectible_ids_tags_exclude);
        $collectible_ids_exclude = array_unique($collectible_ids_exclude);
      }

      // add Collectibles based on Collection IDs
      if (!empty($collection_ids))
      {
        /** @var $q FrontendCollectionCollectibleQuery */
        $q = FrontendCollectionCollectibleQuery::create()
          ->filterByCollectionId($collection_ids, Criteria::IN)
          ->select('CollectibleId');

        $_collectible_ids_collection= $q->find()->toArray();

        $collectible_ids = array_merge($collectible_ids, $_collectible_ids_collection);
        $collectible_ids = array_unique($collectible_ids);
      }

      // exclude Collectibles based on Collection IDs
      if (!empty($collection_ids_exclude))
      {
        /** @var $q FrontendCollectionCollectibleQuery */
        $q = FrontendCollectionCollectibleQuery::create()
          ->filterByCollectionId($collection_ids_exclude, Criteria::IN)
          ->filterByCollectibleId($homepage_collectible_ids, Criteria::NOT_IN)
          ->select('CollectibleId');

        $_collectible_ids_collection_exclude = $q->find()->toArray();

        $collectible_ids_exclude = array_merge($collectible_ids_exclude, $_collectible_ids_collection_exclude);
        $collectible_ids_exclude = array_unique($collectible_ids_exclude);
      }

      /** @var $q FrontendCollectionCollectibleQuery */
      $q = FrontendCollectionCollectibleQuery::create()
        ->select('CollectibleId')
        ->filterByCollectibleId($collectible_ids, Criteria::IN)
        ->_and()
        ->filterByCollectibleId($collectible_ids_exclude, Criteria::NOT_IN);

      if (!empty($homepage_collectible_ids))
      {
        $q->addDescendingOrderByColumn(
          'FIELD(collection_collectible.collectible_id, ' . implode(',', array_reverse($homepage_collectible_ids)) . ')'
        );
      }

      /** @var $collectible_ids array */
      $_collectible_ids = $q->find()->toArray();

      // Cache the result for the life of the session
      $this->getUser()->setAttribute('featured_items_collectible_ids_' . $post_id, $_collectible_ids, 'cache');
    }

    // We cannot show a custom page without custom Collectible IDs
    $this->forward404Unless(is_array($_collectible_ids));

    $q = FrontendCollectibleQuery::create()
      ->filterById($_collectible_ids)
      ->addAscendingOrderByColumn(
        'FIELD(collectible.id, ' . implode(',', $_collectible_ids) . ')'
      );

    $pager = new PropelModelPager($q, $limit);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->wp_post = $wp_post;

    $this->collectibles_2x2 = $collectibles_2x2;
    $this->collectibles_2x1 = $collectibles_2x1;
    $this->collectibles_1x2 = $collectibles_1x2;

    $this->addBreadcrumb($wp_post->getPostTitle(), null);

    $title = $wp_post->getPostMetaValue('_yoast_wpseo_title') ?:
             $wp_post->getPostTitle();
    $this->getResponse()->setTitle($title);
    $this->getResponse()->addOpenGraphMetaFor($wp_post);

    return sfView::SUCCESS;
  }

}
