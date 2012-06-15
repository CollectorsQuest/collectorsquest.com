<?php

class collectionsActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header_main_menu', 'collections');
  }

  public function executeIndex()
  {
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
    $this->collector = $this->getRoute()->getObject();

    $q = CollectorCollectionQuery::create()
        ->filterByCollector($this->collector)
        ->orderByUpdatedAt(Criteria::DESC);

    $pager = new PropelModelPager($q, 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

    return sfView::SUCCESS;
  }

}
