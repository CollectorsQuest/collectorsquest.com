<?php

class categoriesActions extends cqFrontendActions
{
  public function executeIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeCategory(sfWebRequest $request)
  {
    $this->category = $this->getRoute()->getObject();

    $q = CollectorCollectionQuery::create()
       ->filterByCollectionCategory($this->category);

    $pager = new PropelModelPager($q, 16);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

    return sfView::SUCCESS;
  }
}
