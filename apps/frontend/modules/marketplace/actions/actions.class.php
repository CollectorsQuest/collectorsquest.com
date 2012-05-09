<?php

class marketplaceActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $q = CollectibleForSaleQuery::create()
      ->joinCollectible()
      ->isForSale()
      ->orderByUpdatedAt(Criteria::DESC);

    $this->spotlight = $q->limit(3)->find();
    $this->collectibles_for_sale = $q->limit(12)->find();



    return sfView::SUCCESS;
  }

  public function executeBrowse(sfWebRequest $request)
  {
    $content_category = $this->getRoute()->getObject();

    $q = CollectibleForSaleQuery::create()
       ->joinCollectible()
       ->filterByContentCategoryWithDescendants($content_category)
       ->isForSale()
       ->orderByUpdatedAt(Criteria::DESC);

    $search = array();
    if ($request->getParameter('page'))
    {
      $search = $this->getUser()->getAttribute('search', array(), 'marketplace');
    }

    if ($search['price'] = $this->getRequestParameter('price', (array) @$search['price']))
    {
      $q->filterByPrice($search['price']);
    }
    if ($search['condition'] = $this->getRequestParameter('condition', @$search['condition']))
    {
      $q->filterByCondition($search['condition']);
    }

    $pager = new PropelModelPager($q, 18);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->content_category = $content_category;

    return sfView::SUCCESS;
  }

  public function executeCategories(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }
}
