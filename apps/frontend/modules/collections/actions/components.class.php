<?php

class collectionsComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    $ids = array(
      3044,  152,  402,  775,
       521, 3465, 1209, 3375,
         2, 1136, 1425, 1559,
      1755, 3464, 1905, 2266,
      2836, 3043,
    );

    $q = ContentCategoryQuery::create()
      ->filterById($ids, Criteria::IN)
      ->orderByName(Criteria::ASC)
      ->limit($this->limit);
    $this->categories = $q->find();

    return sfView::SUCCESS;
  }

  public function executeSidebarCategory()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarCollector()
  {
    return sfView::SUCCESS;
  }

  public function executeFeaturedWeek()
  {
    /** @var $q wpPostQuery */
    $q = wpPostQuery::create()
      ->filterByPostType('featured_week')
      ->filterByPostParent(0)
      ->orderByPostDate(Criteria::DESC);

    if (sfConfig::get('sf_environment') === 'prod')
    {
      $q->filterByPostStatus('publish');
    }

    /** @var $wp_post wpPost */
    if ($wp_post = $q->findOne())
    {
      $values = $wp_post->getPostMetaValue('_featured_week_collectibles');

      if (isset($values['cq_collectible_ids']))
      {
        $collectible_ids = cqFunctions::explode(',', $values['cq_collectible_ids']);

        /** @var $q FrontendCollectibleQuery */
        $q = FrontendCollectibleQuery::create()
          ->filterById($collectible_ids)
          ->limit(4)
          ->addAscendingOrderByColumn(
            'FIELD(collectible.id, ' . implode(',', $collectible_ids) . ')'
          );
        $this->collectibles = $q->find();
      }

      $this->wp_post = $wp_post;
    }

    return $this->wp_post ? sfView::SUCCESS : sfView::NONE;
  }

  public function executeFeaturedWeekCollectibles()
  {
    $wp_post = wpPostQuery::create()->findOneById($this->getRequestParameter('id'));

    if ($wp_post instanceof wpPost)
    {
      $values = $wp_post->getPostMetaValue('_featured_week_collectibles');

      if (isset($values['cq_collectible_ids']))
      {
        $collectible_ids = cqFunctions::explode(',', $values['cq_collectible_ids']);

        /** @var $q CollectibleQuery */
        $q = CollectibleQuery::create()
          ->filterById($collectible_ids)
          ->offset(4)
          ->limit(12)
          ->addAscendingOrderByColumn(
            'FIELD(collectible.id, ' . implode(',', $collectible_ids) . ')'
          );
        $this->collectibles = $q->find();
      }

      $this->wp_post = $wp_post;
    }

    return $this->collectibles ? sfView::SUCCESS : sfView::NONE;
  }

  public function executeExploreCollections()
  {
    $q = (string) $this->getRequestParameter('q');
    $s = (string) $this->getRequestParameter('s', 'most-recent');
    $p = (int) $this->getRequestParameter('p', 1);
    $pager = null;

    if ($s != 'most-recent')
    {
      $query = array(
        'q' => $q,
        'filters' => array(
          'has_thumbnail' => true,
          'is_public' => true
        ),
      );

      switch ($s)
      {
        case 'most-relevant':
          $query['sortby'] = 'relevance';
          $query['order'] = 'desc';
          break;
        case 'most-popular':
          $query['sortby'] = 'popularity';
          $query['order'] = 'desc';
          break;
        case 'most-recent':
        default:
          $query['sortby'] = 'date';
          $query['order'] = 'desc';
          break;
      }

      $pager = new cqSphinxPager($query, array('collections'), 16);
    }
    else if (false)
    {
      /** @var $query wpPostQuery */
      $query = wpPostQuery::create()
        ->filterByPostType('collections_explore')
        ->filterByPostParent(0)
        ->orderByPostDate(Criteria::DESC);

      if (sfConfig::get('sf_environment') === 'prod')
      {
        $query->filterByPostStatus('publish');
      }

      /** @var $wp_post wpPost */
      if ($wp_post = $query->findOne())
      {
        $values = $wp_post->getPostMetaValue('_collections_explore_items');

        if (isset($values['cq_collection_ids']))
        {
          $collection_ids = cqFunctions::explode(',', $values['cq_collection_ids']);

          // Adding American Pickers and Pawn Stars at the top
          $collection_ids = array_merge(array(2842, 2841), $collection_ids);

          /** @var $query FrontendCollectorCollectionQuery */
          $query = FrontendCollectorCollectionQuery::create()
            ->filterById($collection_ids)
            ->hasThumbnail()
            ->addAscendingOrderByColumn('FIELD(collector_collection.id, '. implode(',', $collection_ids) .')');

          $pager = new PropelModelPager($query, 16);
        }

        $this->wp_post = $wp_post;
      }
    }
    else
    {
      $query = FrontendCollectorCollectionQuery::create()
        ->hasThumbnail()
        ->groupByCollectorId()
        ->orderByCreatedAt(Criteria::DESC);

      // Temporary filter out Guruzen's collections
      $query->filterByCollectorId(4267, Criteria::NOT_EQUAL);

      $pager = new PropelModelPager($query, 16);
    }

    if ($pager)
    {
      $pager->setPage($p);
      $pager->init();

      $this->pager = $pager;
      $this->url = '@search_collections?q='. $q . '&s='. $s .'&page=1';

      return sfView::SUCCESS;
    }

    return sfView::NONE;
  }
}
