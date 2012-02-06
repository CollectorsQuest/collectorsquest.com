<?php

/**
 * general actions.
 *
 * @package    CollectorsQuest
 * @subpackage general
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class searchActions extends cqActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  * @return string
  */
  public function executeIndex(sfWebRequest $request)
  {
    $types = $request->getParameter('types', array('collectibles', 'collections', 'collectors', 'blog'));

    /**
     * @var SphinxClient $sphinx
     * @var string $q
     */
    list($sphinx, $q) = $this->_get_sphinx($request);

    // Set the totals array
    $totals = array();
    $pagers = array();

    /**
     * Collectibles
     */
    if (in_array('collectibles', $types) || empty($types))
    {
      $sphinx->setLimits(0, 24);
      $totals['collectibles'] = $this->_search_collectibles($sphinx, $q);

      $pagers['collectibles'] = new cqPropelPager('Collectible', 24);
      $pagers['collectibles']->setPage(1);
      $pagers['collectibles']->setNbResults($totals['collectibles']);
      $pagers['collectibles']->init();

      $totals['collectibles'] = $this->_search_collectibles($sphinx, $q);
    }

    /**
     * Collections
     */
    if (in_array('collections', $types) || empty($types))
    {
      $sphinx->setLimits(0, 12);
      $totals['collections'] = $this->_search_collections($sphinx, $q);

      $pagers['collections'] = new cqPropelPager('Collection', 12);
      $pagers['collections']->setPage(1);
      $pagers['collections']->setNbResults($totals['collections']);
      $pagers['collections']->init();
    }

    /**
     * Collectors
     */
    if (in_array('collectors', $types) || empty($types))
    {
      $sphinx->setLimits(0, 8);
      $totals['collectors'] = $this->_search_collectors($sphinx, $q);

      $pagers['collectors'] = new cqPropelPager('Collector', 8);
      $pagers['collectors']->setPage(1);
      $pagers['collectors']->setNbResults($totals['collectors']);
      $pagers['collectors']->init();
    }

    /**
     * Blog Posts
     */
    if (in_array('blog', $types) || empty($types))
    {
      $sphinx->setLimits(0, 10);
      $totals['blog'] = $this->_search_blog($sphinx, $q);

      $pagers['blog'] = new cqPropelPager('wpPost', 10);
      $pagers['blog']->setPage(1);
      $pagers['blog']->setNbResults($totals['blog']);
      $pagers['blog']->init();
    }

    // Make the totals and tabs array available to the template
    $this->totals = $totals;
    $this->tabs = array_keys(array_filter($totals));

    // Make the pagers array available to the template
    $this->pagers = $pagers;

    $this->q = $q;

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Search'), '@search_advanced');
    $this->addBreadcrumb(sprintf($this->__('Searching for %s'), $request->getParameter('q', $request->getParameter('tag'))));

    // Building the title
    $this->prependTitle($this->__('Search'));
    $this->prependTitle($request->getParameter('q', $request->getParameter('tag')));

    $totals = array_filter($totals);
    if (empty($totals))
    {
      $c = new Criteria();
      $c->add(CollectionPeer::IS_PUBLIC, true);
      $c->addAscendingOrderByColumn(CollectionPeer::SCORE);
      $c->setLimit(9);

      $this->collections = CollectionPeer::doSelect($c);

      return 'NoResults';
    }

    return sfView::SUCCESS;
  }

  public function executeAdvanced(sfWebRequest $request)
  {
    $this->form = new SearchAdvancedForm();
    $this->form->setDefault('q', $request->getParameter('q'));

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Search'));

    // Building the title
    $this->prependTitle($this->__('Search'));

    return sfView::SUCCESS;
  }

  public function executeCollectibles(sfWebRequest $request)
  {
    /**
     * @var SphinxClient $sphinx
     * @var string $q
     */
    list($sphinx, $q) = $this->_get_sphinx($request);

    $per_page = ($request->getParameter('show') == 'all') ? 1000 : 24;
    $page = $this->getRequestParameter('page', 1);

    $sphinx->setLimits(($page-1) * 24, $per_page);
    $this->total = $this->_search_collectibles($sphinx, $q);

    $pager = new cqPropelPager('Collectible', $per_page);
    $pager->setPage($page);
    $pager->setNbResults($this->total);
    $pager->init();

    $this->pager = $pager;
    $this->q = $q;

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Search'), '@search_advanced');
    $this->addBreadcrumb(sprintf($this->__('Searching for %s'), $request->getParameter('q', $request->getParameter('tag'))));

    // Building the title
    $this->prependTitle($this->__('Search'));
    $this->prependTitle($request->getParameter('q', $request->getParameter('tag')));

    return sfView::SUCCESS;
  }

  /**
   * @param sfWebRequest $request
   * @return string
   */
  public function executeCollections(sfWebRequest $request)
  {
    /**
     * @var SphinxClient $sphinx
     * @var string $q
     */
    list($sphinx, $q) = $this->_get_sphinx($request);

    $per_page = ($request->getParameter('show') == 'all') ? 1000 : 12;
    $page = $this->getRequestParameter('page', 1);

    $sphinx->setLimits(($page-1) * 12, $per_page);
    $this->total = $this->_search_collections($sphinx, $q);

    $pager = new cqPropelPager('Collection', $per_page);
    $pager->setPage($page);
    $pager->setNbResults($this->total);
    $pager->init();

    $this->pager = $pager;
    $this->q = $q;

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Search'), '@search_advanced');
    $this->addBreadcrumb(sprintf($this->__('Searching for %s'), $request->getParameter('q', $request->getParameter('tag'))));

    // Building the title
    $this->prependTitle($this->__('Search'));
    $this->prependTitle($request->getParameter('q', $request->getParameter('tag')));

    return sfView::SUCCESS;
  }

  /**
   * @param sfWebRequest $request
   * @return string
   */
  public function executeCollectors(sfWebRequest $request)
  {
    /**
     * @var SphinxClient $sphinx
     * @var string $q
     */
    list($sphinx, $q) = $this->_get_sphinx($request);

    $per_page = ($request->getParameter('show') == 'all') ? 1000 : 8;
    $page = $this->getRequestParameter('page', 1);

    $sphinx->setLimits(($page-1) * 8, $per_page);
    $this->total = $this->_search_collectors($sphinx, $q);

    $pager = new cqPropelPager('Collector', $per_page);
    $pager->setPage($page);
    $pager->init();

    $this->pager = $pager;
    $this->q = $q;

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Search'), '@search_advanced');
    $this->addBreadcrumb(sprintf($this->__('Searching for %s'), $request->getParameter('q', $request->getParameter('tag'))));

    // Building the title
    $this->prependTitle($this->__('Search'));
    $this->prependTitle($request->getParameter('q', $request->getParameter('tag')));

    return sfView::SUCCESS;
  }

  /**
   * @param sfWebRequest $request
   * @return string
   */
  public function executeBlog(sfWebRequest $request)
  {
    /**
     * @var SphinxClient $sphinx
     * @var string $q
     */
    list($sphinx, $q) = $this->_get_sphinx($request);

    $per_page = ($request->getParameter('show') == 'all') ? 1000 : 12;
    $page = $this->getRequestParameter('page', 1);

    $sphinx->setLimits(($page-1) * 12, $per_page);
    $this->total = $this->_search_blog($sphinx, $q);

    $pager = new sfPropelPager('wpPost', $per_page);
    $pager->setPage($page);
    $pager->init();

    $this->pager = $pager;
    $this->q = $q;

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Search'), '@search_advanced');
    $this->addBreadcrumb(sprintf($this->__('Searching for %s'), $request->getParameter('q', $request->getParameter('tag'))));

    // Building the title
    $this->prependTitle($this->__('Search'));
    $this->prependTitle($request->getParameter('q', $request->getParameter('tag')));

    return sfView::SUCCESS;
  }

  private function _get_sphinx(sfWebRequest $request)
  {
    $q = $request->getParameter('q', $request->getParameter('tag'));

    if (empty($q) && $request->isMethod('get'))
    {
      return $this->redirect('@search_advanced');
    }

    $sphinx = cqStatic::getSphinxClient();

    // http://www.sphinxsearch.com/docs/current.html#api-func-setmatchmode
    $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);

    // http://www.sphinxsearch.com/docs/current.html#api-func-setarrayresult
    $sphinx->setArrayResult(false);

    // http://www.sphinxsearch.com/docs/current.html#api-func-setconnecttimeout
    $sphinx->setConnectTimeout(3);

    // http://www.sphinxsearch.com/docs/current.html#api-func-resetfilters
    $sphinx->resetFilters();

    $q = strip_tags($q);
    $q = str_replace(array('+', '\\', '/', '!', '(', ')', '  '), ' ', $q);
    $q = $sphinx->escapeString(trim($q));

    return array($sphinx, $q);
  }

  /**
   * @param  SphinxClient $sphinx
   * @param  string $q
   *
   * @return integer
   */
  private function _search_collectibles(SphinxClient $sphinx, $q)
  {
    $this->collectibles = array();

    // http://www.sphinxsearch.com/docs/current.html#api-func-setsortmode
    $sphinx->setSortMode(
      SPH_SORT_EXTENDED,
      'score DESC, @relevance DESC, created_at DESC, @id DESC'
    );

    // http://www.sphinxsearch.com/docs/current.html#api-func-query
    $result = $sphinx->query(trim($q, ' &'), sprintf('%s_collectibles', sfConfig::get('sf_environment')));

    if ($result['total'] > 0)
    {
      if (isset($search['sortby']) && $search['sortby'] == 'relevance')
      {
        // Sort by score (descending order)
        uasort(
          $result['matches'],
          create_function('$x, $y', 'if (!isset($x["score"]) || $x["score"] == $y["score"]) return 0; else if ($x["score"] > $y["score"]) return -1; else return 1;')
        );

        // Sort by weight (descending order)
        uasort(
          $result['matches'],
          create_function('$x, $y', 'if (!isset($x["weight"]) || $x["weight"] == $y["weight"]) return 0; else if ($x["weight"] > $y["weight"]) return -1; else return 1;')
        );
      }

      if (!empty($result['matches']))
      {
        // The the collectibles pks
        $pks = array_keys($result['matches']);

        $c = new Criteria;
        $c->add(CollectiblePeer::ID, $pks, Criteria::IN);
        $c->addAscendingOrderByColumn(sprintf('FIELD(%s, %s)', CollectiblePeer::ID, implode(', ', $pks)));

        $this->collectibles = CollectiblePeer::doSelect($c);
      }
    }

    return $result['total'];
  }

  /**
   * @param  SphinxClient $sphinx
   * @param  string $q
   *
   * @return integer
   */
  private function _search_collections(SphinxClient $sphinx, $q)
  {
    $this->collections = array();

    // http://www.sphinxsearch.com/docs/current.html#api-func-setsortmode
    $sphinx->setSortMode(
      SPH_SORT_EXTENDED,
      'score DESC, @relevance DESC, created_at DESC, @id DESC'
    );

    // http://www.sphinxsearch.com/docs/current.html#api-func-query
    $result = $sphinx->query(trim($q, ' &'), sprintf('%s_collections', sfConfig::get('sf_environment')));

    if ($result['total'] > 0)
    {
      if (isset($search['sortby']) && $search['sortby'] == 'relevance')
      {
        // Sort by score (descending order)
        uasort(
          $result['matches'],
          create_function('$x, $y', 'if (!isset($x["score"]) || $x["score"] == $y["score"]) return 0; else if ($x["score"] > $y["score"]) return -1; else return 1;')
        );

        // Sort by weight (descending order)
        uasort(
          $result['matches'],
          create_function('$x, $y', 'if (!isset($x["weight"]) || $x["weight"] == $y["weight"]) return 0; else if ($x["weight"] > $y["weight"]) return -1; else return 1;')
        );
      }

      if (!empty($result['matches']))
      {
        // The the collections pks
        $pks = array_keys($result['matches']);

        $c = new Criteria;
        $c->add(CollectionPeer::ID, $pks, Criteria::IN);
        $c->addAscendingOrderByColumn(sprintf('FIELD(%s, %s)', CollectionPeer::ID, implode(', ', $pks)));

        $this->collections = CollectionPeer::doSelect($c);
      }
    }

    return $result['total'];
  }

  /**
   * @param  SphinxClient $sphinx
   * @param  string $q
   *
   * @return integer
   */
  private function _search_collectors(SphinxClient $sphinx, $q)
  {
    $this->collectors = array();

    // http://www.sphinxsearch.com/docs/current.html#api-func-setsortmode
    $sphinx->setSortMode(
      SPH_SORT_EXTENDED, '@relevance DESC, @id DESC'
    );

    // http://www.sphinxsearch.com/docs/current.html#api-func-query
    $result = $sphinx->query(trim($q, ' &'), sprintf('%s_collectors', sfConfig::get('sf_environment')));

    if ($result['total'] > 0)
    {
      if (isset($search['sortby']) && $search['sortby'] == 'relevance')
      {
        // Sort by score (descending order)
        uasort(
          $result['matches'],
          create_function('$x, $y', 'if (!isset($x["score"]) || $x["score"] == $y["score"]) return 0; else if ($x["score"] > $y["score"]) return -1; else return 1;')
        );

        // Sort by weight (descending order)
        uasort(
          $result['matches'],
          create_function('$x, $y', 'if (!isset($x["weight"]) || $x["weight"] == $y["weight"]) return 0; else if ($x["weight"] > $y["weight"]) return -1; else return 1;')
        );
      }

      if (!empty($result['matches']))
      {
        // The the collectors pks
        $pks = array_keys($result['matches']);

        $c = new Criteria;
        $c->add(CollectorPeer::ID, $pks, Criteria::IN);
        $c->addAscendingOrderByColumn(sprintf('FIELD(%s, %s)', CollectorPeer::ID, implode(', ', $pks)));

        $this->collectors = CollectorPeer::doSelect($c);
      }
    }

    return $result['total'];
  }

  /**
   * @param  SphinxClient $sphinx
   * @param  string $q
   *
   * @return integer
   */
  private function _search_blog(SphinxClient $sphinx, $q)
  {
    $this->blog = array();

    /**
     * @see http://www.sphinxsearch.com/docs/current.html#api-func-setfilter
     */
    $sphinx->SetFilter('isPost', array(1));

    /**
     * @see  http://www.sphinxsearch.com/docs/current.html#api-func-setsortmode
     */
    $sphinx->setSortMode(
      SPH_SORT_EXTENDED, '@relevance DESC, @id DESC'
    );

    // http://www.sphinxsearch.com/docs/current.html#api-func-query
    $result = $sphinx->query(trim($q, ' &'), sprintf('%s_blog', sfConfig::get('sf_environment')));

    if ($result['total'] > 0)
    {
      if (isset($search['sortby']) && $search['sortby'] == 'relevance')
      {
        // Sort by weight (descending order)
        uasort(
          $result['matches'],
          create_function('$x, $y', 'if (!isset($x["weight"]) || $x["weight"] == $y["weight"]) return 0; else if ($x["weight"] > $y["weight"]) return -1; else return 1;')
        );
      }

      if (!empty($result['matches']))
      {
        // The the posts pks
        $pks = array();
        foreach (array_keys($result['matches']) as $v)
        {
          $pks[] = $v - 100000;
        }

        $c = new Criteria;
        $c->add(wpPostPeer::ID, $pks, Criteria::IN);
        $c->addAscendingOrderByColumn(sprintf('FIELD(%s, %s)', wpPostPeer::ID, implode(', ', $pks)));

        $this->blog = wpPostPeer::doSelect($c);
      }
    }

    return $result['total'];
  }
}
