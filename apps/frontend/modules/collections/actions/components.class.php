<?php

class collectionsComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    $ids = array(
      3044,  152,  402,  775,
       521, 3465, 1209, 3375,
       885, 1136, 1425, 1559,
      1755, 3464, 1905, 2266,
      2836, 3043,    2
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
    $q = wpPostQuery::create()
      ->filterByPostType('featured_week')
      ->filterByPostStatus('publish')
      ->orderByPostDate(Criteria::DESC);

    /** @var $wp_post wpPost */
    if ($wp_post = $q->findOne())
    {
      $values = unserialize($wp_post->getPostMetaValue('_featured_week_collectibles'));

      if (isset($values['cq_collectible_ids']))
      {
        $collectible_ids = explode(',', (string) $values['cq_collectible_ids']);
        $collectible_ids = array_map('trim', $collectible_ids);
        $collectible_ids = array_filter($collectible_ids);

        $q = CollectibleQuery::create()
          ->filterById($collectible_ids)
          ->limit(4);
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
      $values = unserialize($wp_post->getPostMetaValue('_featured_week_collectibles'));

      if (isset($values['cq_collectible_ids']))
      {
        $collectible_ids = explode(',', (string) $values['cq_collectible_ids']);
        $collectible_ids = array_map('trim', $collectible_ids);
        $collectible_ids = array_filter($collectible_ids);

        $q = CollectibleQuery::create()
          ->filterById($collectible_ids)
          ->offset(4)
          ->limit(12);
        $this->collectibles = $q->find();
      }

      $this->wp_post = $wp_post;
    }

    return $this->collectibles ? sfView::SUCCESS : sfView::NONE;
  }

  public function executeExploreCollections()
  {
    $q = (string) $this->getRequestParameter('q');
    $s = (string) $this->getRequestParameter('s', 'most-relevant');
    $p = (int) $this->getRequestParameter('p', 1);
    $pager = null;

    if (!empty($q) || $s != 'most-relevant')
    {
      $query = array(
        'q' => $q,
        'filters' => array()
      );

      switch ($s)
      {
        case 'most-recent':
          $query['sortby'] = 'date';
          $query['order'] = 'desc';
          break;
        case 'most-popular':
          $query['sortby'] = 'popularity';
          $query['order'] = 'desc';
          break;
        case 'most-relevant':
        default:
          $query['sortby'] = 'relevance';
          $query['order'] = 'desc';
          break;
      }

      $pager = new cqSphinxPager($query, array('collections'), 16);
    }
    else
    {
      $query = wpPostQuery::create()
        ->filterByPostType('collections_explore')
        ->filterByPostStatus('publish')
        ->orderByPostDate(Criteria::DESC);

      /** @var $wp_post wpPost */
      if ($wp_post = $query->findOne())
      {
        $values = unserialize($wp_post->getPostMetaValue('_collections_explore_items'));

        if (isset($values['cq_collection_ids']))
        {
          $collection_ids = explode(',', (string) $values['cq_collection_ids']);
          $collection_ids = array_map('trim', $collection_ids);
          $collection_ids = array_filter($collection_ids);

          // Adding American Pickers and Pawn Stars at the top
          $collection_ids = array_merge(array(2842, 2841), $collection_ids);

          $query = CollectorCollectionQuery::create()
            ->filterById($collection_ids)
            ->addAscendingOrderByColumn('FIELD(id, '. implode(',', $collection_ids) .')');

          $pager = new PropelModelPager($query, 16);
        }

        $this->wp_post = $wp_post;
      }
    }

    if ($pager)
    {
      $pager->setPage($p);
      $pager->init();

      $this->pager = $pager;
      $this->url = '@search_collections?q='. $q . '&s='. $s .'&page='. $pager->getNextPage();

      return sfView::SUCCESS;
    }

    return sfView::NONE;
  }
}
