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

      header("Expires: 0");
      header("Cache-control: private");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Description: File Transfer");
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

    $values = unserialize($wp_post->getPostMetaValue('_featured_items'));

    // Initialize the arrays
    $collection_ids = $collectible_ids = $category_ids = $tags = array();
    $collection_ids_exclude = $collectible_ids_exclude = $category_ids_exclude = $tags_exclude = array();

    if (!empty($values['cq_collection_ids']))
    {
      $collection_ids = cqFunctions::explode(',', $values['cq_collection_ids']);
    }
    if (!empty($values['cq_collectible_ids']))
    {
      $collectible_ids = cqFunctions::explode(',', $values['cq_collectible_ids']);
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

    if (!$_collectible_ids = $this->getUser()->getAttribute('featured_items_collectible_ids_' . $post_id, null, 'cache'))
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
          ->select('Id');

        $_collectible_ids_tags_exclude = $q->find()->toArray();

        $collectible_ids_exclude = array_merge($collectible_ids_exclude, $_collectible_ids_tags_exclude);
        $collectible_ids_exclude = array_unique($collectible_ids_exclude);
      }

      /** @var $q FrontendCollectionCollectibleQuery */
      $q = FrontendCollectionCollectibleQuery::create()
        ->select('CollectibleId')
        ->filterByCollectionId($collection_ids, Criteria::IN)
        ->_and()
        ->filterByCollectionId($collection_ids_exclude, Criteria::NOT_IN)
        ->_or()
        ->filterByCollectibleId($collectible_ids, Criteria::IN)
        ->_and()
        ->filterByCollectibleId($collectible_ids_exclude, Criteria::NOT_IN);

      if (!empty($homepage_collectible_ids))
      {
        $q->addDescendingOrderByColumn(
          'FIELD(collectible_id, ' . implode(',', array_reverse($homepage_collectible_ids)) . ')'
        );
      }

      /** @var $collectible_ids array */
      $_collectible_ids = $q->find()->toArray();

      // Cache the result for the life of the session
      $this->getUser()->setAttribute('featured_items_collectible_ids_' . $post_id, $_collectible_ids, 'cache');
    }

    // We cannot show a custom page without custom Collectible IDs
    $this->forward404Unless(is_array($_collectible_ids));

    $q = CollectionCollectibleQuery::create()
      ->filterByCollectibleId($_collectible_ids)
      ->addAscendingOrderByColumn(
        'FIELD(collectible_id, ' . implode(',', $_collectible_ids) . ')'
      );

    $pager = new PropelModelPager($q, 20);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->wp_post = $wp_post;

    $this->addBreadcrumb($wp_post->getPostTitle(), null);

    $title = $wp_post->getPostMetaValue('_yoast_wpseo_title') ?:
             $wp_post->getPostTitle();
    $this->getResponse()->setTitle($title);
    $this->getResponse()->addOpenGraphMetaFor($wp_post);

    return sfView::SUCCESS;
  }

}
