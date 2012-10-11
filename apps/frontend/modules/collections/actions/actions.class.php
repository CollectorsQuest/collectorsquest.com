<?php

class collectionsActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header', 'collections');
  }

  public function executeIndex(sfWebRequest $request)
  {
    if ($request->getRequestFormat() === 'rss' || (int) $request->getParameter('page', 0) > 0)
    {
      /** @var $q FrontendCollectorCollectionQuery */
      $q = FrontendCollectorCollectionQuery::create();

      switch ($request->getParameter('sort', 'latest'))
      {
        case 'most-popular':
          $q
            ->withColumn('SUM(CollectorCollection.NumViews)', 'TotalCollectionsViews')
            ->groupBy('CollectorCollection.CollectorId')
            ->orderBy('CollectorCollection.NumViews', Criteria::DESC);
          break;

        case 'latest':
        default:
          $q->orderById(Criteria::DESC);
          break;
      }

      $pager = new cqPropelModelPager($q, 36);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $this->pager = $pager;

      $this->setTemplate('collections');
    }

    return sfView::SUCCESS;
  }

  public function executeCategory()
  {
    $this->category = $this->getRoute()->getObject();

    return sfView::SUCCESS;
  }

  /**
   * Action Collector
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeCollector(sfWebRequest $request)
  {
    /** @var $collector Collector */
    $collector = $this->getRoute()->getObject();

    $q = FrontendCollectorCollectionQuery::create()
        ->filterByCollector($collector)
        ->orderByUpdatedAt(Criteria::DESC);

    $pager = new PropelModelPager($q, 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->collector = $collector;

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('collections_by_collector', $this->collector));

    return sfView::SUCCESS;
  }

}
