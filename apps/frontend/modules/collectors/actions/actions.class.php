<?php

class collectorsActions extends cqFrontendActions
{

  /**
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeIndex(sfWebRequest $request)
  {
    $q = CollectorQuery::create()
          ->orderByCreatedAt(Criteria::DESC);

    $pager = new PropelModelPager($q, 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

    return sfView::SUCCESS;
  }

}
