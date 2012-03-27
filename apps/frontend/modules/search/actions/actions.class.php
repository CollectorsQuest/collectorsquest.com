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

    /**
     * If the user has searched for something specific (self::$_query['q'] is set), then we sort by relevance
     * All other cases it means the user is browsing through the Adverts and we need to sort by date, latest first
     */
    self::$_query['sortby'] = $this->getUser()->getAttribute('sortby', isset(self::$_query['q']) ? 'relevance' : 'date', 'adverts');
    self::$_query['order']  = $this->getUser()->getAttribute('order', 'DESC', 'adverts');

    $pager = new cqSphinxPager(self::$_query, 25);
    $pager->setPage($request->getParameter('page', 1));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->total = ($pager->getNbResults() >= 1000) ? '1000+' : $pager->getNbResults();

    return sfView::SUCCESS;
  }

  public function executeAdvanced(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }
}
