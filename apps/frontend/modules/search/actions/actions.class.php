<?php

class searchActions extends cqFrontendActions
{
  /** @var array */
  private static $_query = array('filters' => array());

  public function preExecute()
  {
    parent::preExecute();

    $request = $this->getRequest();

    self::$_query['q'] = $request->getParameter('q');

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

    switch ($request->getParameter('s'))
    {
      case 'most-recent':
        $this->getUser()->setAttribute('sortby', 'date', 'search');
        $this->getUser()->setAttribute('order', 'desc', 'search');
        break;
      case 'most-popular':
        $this->getUser()->setAttribute('sortby', 'popularity', 'search');
        $this->getUser()->setAttribute('order', 'desc', 'search');
        break;
      case 'most-relevant':
        $this->getUser()->setAttribute('sortby', 'relevance', 'search');
        $this->getUser()->setAttribute('order', 'desc', 'search');
        break;
      default:

        // For now we have taken this outside the IF statements!
        $this->getUser()->setAttribute('sortby', $request->getParameter('sortby'), 'search');
        $this->getUser()->setAttribute('order', $request->getParameter('order'), 'search');

        /**
          if ($request->getParameter('sortby')) {

          }
          if ($request->getParameter('order')) {

          }
        */
        break;
    }

    self::$_query['sortby'] = $this->getUser()->getAttribute('sortby', 'relevance', 'search');

    switch (strtoupper($this->getUser()->getAttribute('order', 'desc', 'search')))
    {
      case 'ASC':
        self::$_query['order']  = 'ASC';
        break;
      case 'DESC':
      default:
        self::$_query['order']  = 'DESC';
        break;
    }
  }

  public function executeIndex(sfWebRequest $request)
  {
    $pager = new cqSphinxPager(self::$_query, array(), 24);
    $pager->setPage($request->getParameter('page', 1));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->total = ($pager->getNbResults() >= 1000) ? '1000+' : $pager->getNbResults();

    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeAdvanced()
  {
    return sfView::SUCCESS;
  }

  public function executeCollections(sfWebRequest $request)
  {
    $pager = new cqSphinxPager(self::$_query, array('collections'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->total = ($pager->getNbResults() >= 1000) ? '1000+' : $pager->getNbResults();

    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeCollectors(sfWebRequest $request)
  {
    $pager = new cqSphinxPager(self::$_query, array('collectors'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->total = ($pager->getNbResults() >= 1000) ? '1000+' : $pager->getNbResults();

    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeCollectibles(sfWebRequest $request)
  {
    $pager = new cqSphinxPager(self::$_query, array('collectibles'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->total = ($pager->getNbResults() >= 1000) ? '1000+' : $pager->getNbResults();

    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeBlog(sfWebRequest $request)
  {
    $pager = new cqSphinxPager(self::$_query, array('blog'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->total = ($pager->getNbResults() >= 1000) ? '1000+' : $pager->getNbResults();

    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeVideos(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

}
