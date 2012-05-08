<?php

class collectionsComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarCategory()
  {
    return sfView::SUCCESS;
  }

  public function executeFeaturedWeek()
  {
    // Featured Week
    if (!$this->featured_week = FeaturedPeer::getCurrentFeatured(FeaturedPeer::TYPE_FEATURED_WEEK))
    {
      $this->featured_week = FeaturedPeer::getLatestFeatured(FeaturedPeer::TYPE_FEATURED_WEEK);
    }
    if ($this->featured_week instanceof Featured)
    {
      $collection_ids = $this->featured_week->getCollectionIds();
      $this->collection = $this->featured_week->getCollections(1);

      if ($this->collection)
      {
        $q = CollectionCollectibleQuery::create()
          ->filterByCollection($this->collection)
          ->limit(4);
        $this->collectibles = $q->find();
      }
    }

    return $this->collection ? sfView::SUCCESS : sfView::NONE;
  }

  public function executeFeaturedWeekCollectibles()
  {
    $collection = CollectorCollectionQuery::create()->findOneById($this->getRequestParameter('collection_id'));

    if ($collection instanceof CollectorCollection)
    {
      $q = CollectibleQuery::create()
        ->offset(4)
        ->limit(12);
      $this->collectibles = $q->find();
    }

    return $this->collectibles ? sfView::SUCCESS : sfView::NONE;
  }

  public function executeExploreCollections()
  {
    $q = $this->getRequestParameter('q');
    $s = $this->getRequestParameter('s', 'most-relevant');
    $p = $this->getRequestParameter('p', 1);

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
    $pager->setPage($p);
    $pager->init();

    $this->pager = $pager;
    $this->url = '@search_collections?q='. $q . '&s='. $s .'&page='. $pager->getNextPage();

    return sfView::SUCCESS;
  }
}
