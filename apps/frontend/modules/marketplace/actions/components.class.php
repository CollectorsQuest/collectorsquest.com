<?php

class marketplaceComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    if ($id = $this->getRequestParameter('id'))
    {
      $this->category = ContentCategoryQuery::create()->findOneById($id);
    }

    return sfView::SUCCESS;
  }

  public function executeSidebarBrowse()
  {
    $this->category = ContentCategoryQuery::create()->findOneById($this->getRequestParameter('id'));

    return $this->category ? sfView::SUCCESS : sfView::NONE;
  }

  public function executeSidebarCategories()
  {
    return sfView::SUCCESS;
  }

  public function executeIndexSlot2()
  {
    return sfView::SUCCESS;
  }

  public function executeDiscoverCollectiblesForSale()
  {
    $q = $this->getRequestParameter('q');
    $s = $this->getRequestParameter('s', 'most-recent');
    $p = $this->getRequestParameter('p', 1);

    // Initialize the $pager
    $pager = null;

    if (!empty($q) || $s != 'most-recent')
    {
      $query = array(
        'q' => $q,
        'filters' => array(
          'has_thumbnail' => true,
          'is_public' => true,
          'uint1' => 1
        )
      );

      $query['sortby'] = 'date';
      $query['order'] = 'desc';

      switch ($s)
      {
        case 'under-100':
          $query['sortby'] = 'uint2';
          $query['order'] = 'desc';
          $query['filters']['uint2'] = array('max' => 10000);
          break;
        case '100-200':
          $query['sortby'] = 'uint2';
          $query['order'] = 'asc';
          $query['filters']['uint2'] = array('min' => 10100, 'max' => 20000);
          break;
        case 'over-250':
          $query['sortby'] = 'uint2';
          $query['order'] = 'asc';
          $query['filters']['uint2'] = array('min' => 25000);
          break;
        case 'most-recent':
        default:
          $query['sortby'] = 'date';
          $query['order'] = 'desc';
          break;
      }

      $pager = new cqSphinxPager($query, array('collectibles'), 12);
    }
    else if (false)
    {
      /** @var $query wpPostQuery */
      $query = wpPostQuery::create()
        ->filterByPostType('marketplace_explore')
        ->filterByPostParent(0)
        ->orderByPostDate(Criteria::DESC);

      if (sfConfig::get('sf_environment') === 'prod')
      {
        $query->filterByPostStatus('publish');
      }

      /** @var $wp_post wpPost */
      if ($wp_post = $query->findOne())
      {
        $values = $wp_post->getPostMetaValue('_market_explore_items');

        if (isset($values['cq_collectible_ids']))
        {
          $collectible_ids = cqFunctions::explode(',', $values['cq_collectible_ids']);

          /** @var $query FrontendCollectibleQuery */
          $query = FrontendCollectibleQuery::create()
            ->filterById($collectible_ids)
            ->hasThumbnail()
            ->isForSale()
            ->addAscendingOrderByColumn('FIELD(collectible.id, '. implode(',', $collectible_ids) .')');

          $pager = new PropelModelPager($query, 12);
        }

        $this->wp_post = $wp_post;
      }
    }
    else
    {
      /** @var $query FrontendCollectibleQuery */
      $query = FrontendCollectibleQuery::create();

      $query
        ->useCollectionCollectibleQuery()
          ->groupByCollectionId()
        ->endUse();

      $query
        ->useCollectibleForSaleQuery()
          ->isForSale()
          ->orderByMarkedForSaleAt(Criteria::DESC)
          ->orderByCreatedAt(Criteria::DESC)
        ->endUse();

      $query
        ->hasThumbnail()
        ->filterById(null, Criteria::NOT_EQUAL)
        ->orderByCreatedAt(Criteria::DESC)
        ->clearGroupByColumns()
        ->groupBy('CollectorId');

      $pager = new PropelModelPager($query, 12);
    }

    if ($pager)
    {
      $pager->setPage($p);
      $pager->init();

      // if we are trying to get an out of bounds page
      if ($p > $pager->getLastPage())
      {
        // return empty response
        return sfView::NONE;
      }

      $this->pager = $pager;
      $this->url = sprintf(
        '@search_collectibles_for_sale?q=%s&s=%s&page=%d',
        $q, $s, $pager->getNextPage()
      );

      return sfView::SUCCESS;
    }

    return sfView::NONE;
  }

  public function executeHolidaySlot1()
  {
    if (cqGateKeeper::locked('aetn_franks_picks', 'page'))
    {
      $this->menu = array();
    }
    else
    {
      $this->menu = array(
        0 => array(
          'id' => -1, 'active' => true,
          'name' => "Frank's<br/><strong>Picks</strong>", 'slug' => 'franks-picks',
          'content' => 'blah blah',
          'tags' => array()
        )
      );
    }

    /* @var $q wpPostQuery */
    $q = wpPostQuery::create()
      ->filterByPostType('market_theme')
      ->filterByPostParent(0)
      ->filterByPostStatus(array('publish', 'draft'), Criteria::IN)
      ->orderByPostDate(Criteria::DESC);

    if (sfConfig::get('sf_environment') === 'prod')
    {
      $q->filterByPostStatus('publish');
    }

    /* @var $wp_posts wpPost[] */
    $wp_posts = $q->find();

    foreach ($wp_posts as $wp_post)
    {
      $meta = $wp_post->getPostMetaValue('_market_theme');
      $name = !empty($meta['cq_menu_name']) ? $meta['cq_menu_name'] : $wp_post->getPostTitle();

      $this->menu[] = array(
        'id' => $wp_post->getId(),
        'name' => $name,
        'slug' => $wp_post->getSlug(),
        'content' => $wp_post->getPostContent(),
        'tags' => $wp_post->getTags('array')
      );
    }

    return sfView::SUCCESS;
  }

  public function executeHolidayTheme()
  {
    /* @var $t integer */
    $t = (integer) $this->getRequestParameter('t', 0);

    /* @var $page integer */
    $page = (integer) $this->getRequestParameter('p', 1);

    if ($t == 0 && cqGateKeeper::open('aetn_franks_picks', 'page'))
    {
      /* @var $aetn_shows array */
      $aetn_shows = sfConfig::get('app_aetn_shows', array());
      $collection = CollectorCollectionQuery::create()->findOneById($aetn_shows['american_pickers']['franks_picks']);

      $q = FrontendCollectionCollectibleQuery::create()
        ->filterByCollection($collection)
        ->isForSale()
        ->orderByPosition(Criteria::ASC)
        ->orderByUpdatedAt(Criteria::ASC);

      $pager = new PropelModelPager($q);
      $pager->setMaxRecordLimit(4);
      $pager->setPage($page);
      $pager->setMaxPerPage(($page === 1) ? 4 : 6);
      $pager->init();

      $this->pager = $pager;
      $this->t = $t;

      return sfView::SUCCESS;
    }
    else
    {
      $offset = cqGateKeeper::open('aetn_franks_picks', 'page') ? $t-1 : $t;

      /* @var $q wpPostQuery */
      $q = wpPostQuery::create()
        ->filterByPostType('market_theme')
        ->filterByPostParent(0)
        ->filterByPostStatus(array('publish', 'draft'), Criteria::IN)
        ->orderByPostDate(Criteria::DESC)
        ->offset($offset);

      if (sfConfig::get('sf_environment') === 'prod')
      {
        $q->filterByPostStatus('publish');
      }

      /* @var $wp_post wpPost */
      $wp_post = $q->findOne();

      if ($wp_post && ($tags = $wp_post->getTags('array')))
      {
        /* @var $q FrontendCollectionCollectibleQuery */
        $q = FrontendCollectibleQuery::create()
          ->isForSale()
          ->filterByMachineTags($tags, 'market', 'theme')
          ->clearOrderByColumns()
          ->orderByAverageRating(Criteria::DESC)
          ->orderByUpdatedAt(Criteria::DESC);

        $pager = new PropelModelPager($q);
        $pager->setPage($page);
        $pager->setMaxPerPage(($page === 1) ? 5 : 6);
        $pager->init();

        $this->pager = $pager;
        $this->wp_post = $wp_post;
        $this->t = $t;

        return sfView::SUCCESS;
      }
    }

    return sfView::NONE;
  }

  public function executeHolidayCollectiblesForSale()
  {
    $q = $this->getRequestParameter('q');
    $p = $this->getRequestParameter('p', 1);
    $s1 = $this->getRequestParameter('s1');
    $s2 = $this->getRequestParameter('s2');

    // Initialize the $pager
    $pager = null;

    if (!empty($q) || !empty($s1) || !empty($s2))
    {
      $query = array(
        'q' => $q,
        'filters' => array(
          'has_thumbnail' => true,
          'is_public' => true,
          'uint1' => 1
        ),
        'sortby' => 'date',
        'order' => 'desc'
      );

      if (!empty($s1) && ($content_category = ContentCategoryQuery::create()->findOneById((integer) $s1)))
      {
        $query['sortby'] = 'uint4';
        $query['order']  = 'desc';
        $query['filters']['uint3'] = array();

        // Add the descendant categories
        if ($descendants = $content_category->getDescendants())
        {
          $query['filters']['uint3'] = array_values(
            $descendants->toKeyValue('Id', 'Id')
          );
        }

        // Add the level 1 category also
        $query['filters']['uint3'][] = $content_category->getId();
      }

      switch ($s2)
      {
        case 'under-50':
          $query['sortby'] = 'uint2';
          $query['order']  = 'desc';
          $query['filters']['uint2'] = array('max' => 4900);
          break;
        case '50-200':
          $query['sortby'] = 'uint2';
          $query['order']  = 'asc';
          $query['filters']['uint2'] = array('min' => 5000, 'max' => 20000);
          break;
        case '200-500':
          $query['sortby'] = 'uint2';
          $query['order']  = 'asc';
          $query['filters']['uint2'] = array('min' => 20000, 'max' => 50000);
          break;
        case 'over-500':
          $query['sortby'] = 'uint2';
          $query['order']  = 'asc';
          $query['filters']['uint2'] = array('min' => 50100);
          break;
      }

      $pager = new cqSphinxPager($query, array('collectibles'), 16);
      $pager->setJoinWith(array('collectible' => array('CollectibleForSale')));
    }
    else
    {
      /** @var $query FrontendCollectibleQuery */
      $query = FrontendCollectibleQuery::create()
        ->orderByAverageRating(Criteria::DESC)
        ->orderByUpdatedAt(Criteria::DESC);

      $query
        ->useCollectionCollectibleQuery()
          ->groupByCollectionId()
        ->endUse();

      $query
        ->useCollectibleForSaleQuery()
          ->isForSale()
          ->orderByMarkedForSaleAt(Criteria::DESC)
          ->orderByCreatedAt(Criteria::DESC)
        ->endUse()
        ->joinWith('CollectibleForSale');

      $query
        ->hasThumbnail()
        ->filterById(null, Criteria::NOT_EQUAL)
        ->orderByCreatedAt(Criteria::DESC)
        ->clearGroupByColumns()
        ->groupBy('Id');

      $pager = new cqPropelModelPager($query, 16);
    }

    if ($pager)
    {
      $pager->setStrictMode(true);
      $pager->setPage($p);
      $pager->init();

      // if we are trying to get an out of bounds page
      if ($p > 1 && $p > $pager->getLastPage())
      {
        // return empty response
        return sfView::NONE;
      }

      $this->pager = $pager;
      $this->url = sprintf(
        '@search_collectibles_for_sale?q=%s&s1=%s&s2&page=%d',
        $q, $s1, $s2, $pager->getNextPage()
      );

      return sfView::SUCCESS;
    }

    return sfView::NONE;
  }

}
