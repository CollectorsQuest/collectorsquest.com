<?php

class marketplaceComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarCategories()
  {
    return sfView::SUCCESS;
  }

  public function executeDiscoverCollectiblesForSale()
  {
    $q = $this->getRequestParameter('q');
    $s = $this->getRequestParameter('s', 'most-recent');
    $p = $this->getRequestParameter('p', 1);

    $query = array(
      'q' => $q,
      'filters' => array('uint1' => 1)
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
        break;
    }

    $pager = new cqSphinxPager($query, array('collectibles'), 12);
    $pager->setPage($p);
    $pager->init();

    $this->pager = $pager;
    $this->url = '@search_collectibles_for_sale?q='. $q . '&s='. $s .'&page='. $pager->getNextPage();

    return sfView::SUCCESS;
  }
}
