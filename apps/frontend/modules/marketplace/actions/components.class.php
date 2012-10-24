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
    /** @var $q wpPostQuery */
    $q = wpPostQuery::create()
      ->filterByPostType('market_theme')
      ->filterByPostParent(0)
      ->orderByPostDate(Criteria::DESC);

    if (sfConfig::get('sf_environment') === 'prod')
    {
      $q->filterByPostStatus('publish');
    }

    /** @var $wp_posts wpPost[] */
    $wp_posts = $q->limit(4)->find();

    $this->menu = array(
      0 => array('id' => -1, 'name' => "Frank's<br/><strong>Picks</strong>", 'slug' => 'franks-picks')
    );
    foreach ($wp_posts as $wp_post)
    {
      $meta = $wp_post->getPostMetaValue('_market_theme');
      $name = !empty($meta['cq_menu_name']) ? $meta['cq_menu_name'] : $wp_post->getPostTitle();

      $this->menu[] = array('id' => $wp_post->getId(), 'name' => $name, 'slug' => $wp_post->getSlug());
    }

    $q = FrontendCollectibleForSaleQuery::create()
      ->isForSale()
      ->orderByUpdatedAt(Criteria::DESC)
      ->limit(6);

    $this->collectibles_for_sale = $q->find();

    return sfView::SUCCESS;
  }

  public function executeHolidayCollectiblesForSale()
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

      $this->pager = $pager;
      $this->url = sprintf(
        '@search_collectibles_for_sale?q=%s&s=%s&page=%d',
        $q, $s, $pager->getNextPage()
      );

      return sfView::SUCCESS;
    }

    return sfView::NONE;
  }

}
