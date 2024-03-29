<?php

class miscComponents extends cqFrontendComponents
{
  public function executeSidebarWordPressFeaturedItems()
  {
    if (!$wp_post = wpPostQuery::create()->findOneById($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    /** @var $values array */
    $values = $wp_post->getPostMetaValue('_featured_items');

    // Initialize the array
    $wp_post_ids = array();

    if (!empty($values['cq_wp_post_ids']))
    {
      $wp_post_ids = cqFunctions::explode(',', $values['cq_wp_post_ids']);
    }

    $this->wp_post = $wp_post;
    $this->wp_post_ids = $wp_post_ids;

    return sfView::SUCCESS;
  }

  public function executeSidebarWordPressFeaturedWeek()
  {
    /* @var $q wpPostQuery */
    $q = wpPostQuery::create()
      ->filterByPostStatus('publish')
      ->filterByPostType('featured_week')
      ->orderByPostTitle();

    $this->featured_weeks = $q->find();
    $this->wp_post_id = $this->getRequestParameter('id');

    return sfView::SUCCESS;
  }

  public function executeWordPressFeaturedItems()
  {
    /** @var $post_id integer */
    $post_id = $this->getRequest()->getParameter('id');

    if (!$wp_post = wpPostQuery::create()->findOneById($post_id))
    {
      return sfView::NONE;
    }

    $values = $wp_post->getPostMetaValue('_featured_items');

    // Initialize the arrays
    $collection_ids = $collectible_ids = $category_ids = $tags = $homepage_collectible_ids = array();
    $collection_ids_exclude = $collectible_ids_exclude = $category_ids_exclude = $tags_exclude = array();
    $collectibles_2x2 = $collectibles_2x1 = $collectibles_1x2 = $collectibles_1x3 = array();
    $collectibles_2x3 = $collectibles_3x3 = $collectibles_3x2 = $collectibles_3x1 = array();

    // set the number of items on page
    $limit = !empty($values['cq_items_per_page']) ? (int) $values['cq_items_per_page'] : '20';

    // is infinite scroll enabled?
    $this->infinite_scroll = !empty($values['cq_infinite_scroll']) ? $values['cq_infinite_scroll'] === 'true' : false;

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
            case '1x3':
              $collectibles_1x3[] = $parsed_value[0];
              break;
            case '2x3':
              $collectibles_2x3[] = $parsed_value[0];
              break;
            case '3x3':
              $collectibles_3x3[] = $parsed_value[0];
              break;
            case '3x2':
              $collectibles_3x2[] = $parsed_value[0];
              break;
            case '3x1':
              $collectibles_3x1[] = $parsed_value[0];
              break;
            default:
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
            case '1x3':
              $collectibles_1x3[] = $parsed_value[0];
              break;
            case '2x3':
              $collectibles_2x3[] = $parsed_value[0];
              break;
            case '3x3':
              $collectibles_3x3[] = $parsed_value[0];
              break;
            case '3x2':
              $collectibles_3x2[] = $parsed_value[0];
              break;
            case '3x1':
              $collectibles_3x1[] = $parsed_value[0];
              break;
            default:
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
    if (!$_collectible_ids || $status !== 'publish' || !sfConfig::get('sf_cache', 'false'))
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
    if (!is_array($_collectible_ids))
    {
      return sfView::NONE;
    }

    $q = FrontendCollectibleQuery::create()
      ->filterById($_collectible_ids);

    if (!empty($_collectible_ids)) {
      $q->addAscendingOrderByColumn(
        'FIELD(collectible.id, ' . implode(',', $_collectible_ids) . ')'
      );
    }

    $pager = new PropelModelPager($q, $limit);
    $page = $this->getRequest()->getParameter('p', 1);
    $pager->setPage($page);
    $pager->init();

    $this->pager = $pager;
    $this->post_id = $post_id;

    $this->collectibles_2x2 = $collectibles_2x2;
    $this->collectibles_2x1 = $collectibles_2x1;
    $this->collectibles_1x2 = $collectibles_1x2;
    $this->collectibles_1x3 = $collectibles_1x3;
    $this->collectibles_2x3 = $collectibles_2x3;
    $this->collectibles_3x3 = $collectibles_3x3;
    $this->collectibles_3x2 = $collectibles_3x2;
    $this->collectibles_3x1 = $collectibles_3x1;

    // if we are trying to get an out of bounds page
    if ($page > 1 && $page > $pager->getLastPage())
    {
      // return empty response
      return sfView::NONE;
    }

    return sfView::SUCCESS;
  }

  public function executeWordPressFeaturedItemsSlot1()
  {
    if (!$this->wp_post = wpPostQuery::create()->findOneById($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    return sfView::SUCCESS;
  }

  public function executeWordPressFeaturedWeeksSlot1()
  {
    return sfView::SUCCESS;
  }

}
