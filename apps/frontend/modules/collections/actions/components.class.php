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
    $q = CollectibleQuery::create()->limit(4);
    $this->collectibles = $q->find();

    return sfView::SUCCESS;
  }

  public function executeFeaturedWeekCollectibles()
  {
    $q = CollectibleQuery::create()->limit(12);
    $this->collectibles = $q->find();

    return sfView::SUCCESS;
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
