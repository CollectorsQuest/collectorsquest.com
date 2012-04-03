<?php

class searchActions extends cqFrontendActions
{
  /** @var array */
  private static $_query = array('filters' => array());

  public function executeIndex(sfWebRequest $request)
  {
    if (($sid = $request->getParameter('sid')) && strlen($sid) == 32)
    {
      $_query = $this->getUser()->getAttribute($sid, array(), 'adverts');
      if (empty($_query) && ($search_history = SearchHistoryQuery::create()->filterBySearchId($sid)->findOne()))
      {
        $_query = $search_history->getSearchCriteria();
      }

      self::$_query = sfToolkit::arrayDeepMerge(self::$_query, IceFunctions::array_filter_recursive($_query));
    }
    else
    {
      if ($q = $request->getParameter('q'))
      {
        self::$_query['q'] = $q;
      }
    }

    if (empty(self::$_query['q']))
    {
      $this->redirect('@search_advanced');
    }

    // Setting the user preference for the adverts display type (grid or list)
    if ($request->getParameter('display'))
    {
      switch ($request->getParameter('display'))
      {
        case 'grid':
          $this->getUser()->setAttribute('display', 'grid', 'search');
          break;
        case 'list':
        default:
          $this->getUser()->setAttribute('display', 'list', 'search');
          break;
      }
    }
    if ($request->getParameter('sortby'))
    {
      $this->getUser()->setAttribute('sortby', $request->getParameter('sortby'), 'search');
    }

    $pager = new cqSphinxPager(self::$_query, 24);
    $pager->setPage($request->getParameter('page', 1));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->total = ($pager->getNbResults() >= 1000) ? '1000+' : $pager->getNbResults();
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');

    return sfView::SUCCESS;
  }

  public function executeAdvanced()
  {
    return sfView::SUCCESS;
  }

  public function executeCollectors(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  public function executeCollectibles(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  public function executeBlog(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  public function executeVideos(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

}
