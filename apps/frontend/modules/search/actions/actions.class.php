<?php

class searchActions extends cqFrontendActions
{
  /** @var array */
  private static $_query = array(
    'filters' => array(
      'has_thumbnail' => 'yes',
      'is_public' => true
    )
  );

  public function preExecute()
  {
    parent::preExecute();

    $request = $this->getRequest();

    if ($tag = $request->getParameter('tag'))
    {
      $request->setParameter('q', str_replace('-', ' ', $tag));
    }

    self::$_query['q'] = $request->getParameter('q');

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
    /** @var $page integer */
    $page = $request->getParameter('page', 1);

    $query = array(
      'q' => self::$_query['q'],
      'limits' => array(4 * (min($page, 42) - 1), 4),
      'filters' => array(
        'object_type' => 'collectible',
        'has_thumbnail' => 'yes',
        'uint1' => 1
      )
    );

    if (
      ($pks = cqSphinxPager::search($query, array('collectibles'), 'pks')) &&
      count($pks) >= 3 &&
      $query['limits'][0] <= 1000
    ) {
      $pks = array_map(create_function('$v', 'return $v - 400000000;'), $pks);

      $this->collectibles_for_sale = CollectibleForSaleQuery::create()
        ->filterByCollectibleId($pks, Criteria::IN)
        ->limit(4)
        ->find();

      self::$_query['filters']['id'] = array($this->collectibles_for_sale->getPrimaryKeys(), true);
    }
    else
    {
      $this->collectibles_for_sale = array();
    }

    $pager = new cqSphinxPager(self::$_query, array(), 24);
    $pager->setPage($page);
    $pager->setStrictMode('all' === $request->getParameter('show'));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    if ($pager->getNbResults() === 0)
    {
      return 'NoResults';
    }

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
    $pager->setStrictMode('all' === $request->getParameter('show'));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeCollectors(sfWebRequest $request)
  {
    $pager = new cqSphinxPager(self::$_query, array('collectors'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $pager->setStrictMode('all' === $request->getParameter('show'));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeCollectibles(sfWebRequest $request)
  {
    $pager = new cqSphinxPager(self::$_query, array('collectibles'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $pager->setStrictMode('all' === $request->getParameter('show'));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeCollectiblesForSale(sfWebRequest $request)
  {
    self::$_query['filters']['uint1'] = 1;

    $pager = new cqSphinxPager(self::$_query, array('collectibles'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $pager->setStrictMode('all' === $request->getParameter('show'));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeBlog(sfWebRequest $request)
  {
    $pager = new cqSphinxPager(self::$_query, array('blog'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $pager->setStrictMode('all' === $request->getParameter('show'));
    $this->sid = $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

  public function executeVideos(sfWebRequest $request)
  {
    $pager = new cqMagnifyPager($request->getParameter('q'), 24);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->url = new IceTypeUrl($request->getUri());

    return sfView::SUCCESS;
  }

}
