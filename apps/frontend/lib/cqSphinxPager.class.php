<?php

class cqSphinxPager extends sfPager
{
  private
    $query   = array(),
    $matches = array(),
    $sid     = null,
    $offset  = null;

  /**
   * @param  array    $query
   * @param  integer  $maxPerPage
   */
  public function __construct($query, $maxPerPage = 14)
  {
    parent::__construct($maxPerPage);

    $this->query = $query;
  }

  /**
   * A function to be called after parameters have been set
   *
   * @return string
   */
  public function init()
  {
    $hasMaxRecordLimit = ($this->getMaxRecordLimit() !== false);
    $maxRecordLimit = $this->getMaxRecordLimit();

    $total = self::search($this->query, 'total');
    $this->setNbResults($total);

    if (($this->getPage() == 0 || $this->getMaxPerPage() == 0))
    {
      $this->setLastPage(0);
    }
    else
    {
      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));

      $offset = $this->getOffset();

      if ($hasMaxRecordLimit)
      {
        $maxRecordLimit = $maxRecordLimit - $offset;
        if ($maxRecordLimit > $this->getMaxPerPage())
        {
          $limit = $this->getMaxPerPage();
        }
        else
        {
          $limit = $maxRecordLimit;
        }
      }
      else
      {
        $limit= $this->getMaxPerPage();
      }

      // Set the number of results to the real number (can be bigger than 1000 here)
      $this->setNbResults($hasMaxRecordLimit ? min($total, $maxRecordLimit) : $total);

      $this->query = array_merge($this->query, array('limits' => array($offset, $limit)));
    }

    // Populate the matches array
    $results = self::search($this->query, 'raw');

    $this->matches = isset($results['matches']) ? $results['matches'] : array();
    $this->words   = isset($results['words'])   ? $results['words']   : array();
    //$this->sid = SearchHistoryPeer::save($this->query, $total);

    return $this->sid;
  }

  public function retrieveObject($offset)
  {
    return null;
  }

  /**
   * Return an array of result on the given page
   *
   * @return array
   */
  public function getResults()
  {
    // Have we populated the matches array yet?
    if (empty($this->matches)) return array();

    $objects         = array();
    $wp_post_ids     = array();
    $collection_ids  = array();
    $collector_ids   = array();
    $collectible_ids = array();

    $ids = array_keys($this->matches);
    foreach ($ids as $i => $id)
    {
      if ($id > 100000000 && $id < 200000000)
      {
        $wp_post_ids[] = $id - 100000000;
      }
      else if ($id > 200000000 && $id < 300000000)
      {
        $collection_ids[] = $id - 200000000;
      }
      else if ($id > 300000000 && $id < 400000000)
      {
        $collector_ids[] = $id - 300000000;
      }
      else if ($id > 400000000 && $id < 500000000)
      {
        $collectible_ids[] = $id - 400000000;
      }

      $objects[$i] = $id;
    }

    if (!empty($wp_post_ids))
    {
      /** @var $wp_posts wpPost[] */
      $wp_posts = wpPostQuery::create()->filterById($wp_post_ids, Criteria::IN)->find();

      foreach ($wp_posts as $wp_post)
      {
        if (false !== $key = array_search($wp_post->getId() + 100000000, $objects, true))
        {
          $objects[$key] = $wp_post;
        }
      }
    }
    if (!empty($collection_ids))
    {
      /** @var $collections Collection[] */
      $collections = CollectionQuery::create()->filterById($collection_ids, Criteria::IN)->find();

      foreach ($collections as $collection)
      {
        if (false !== $key = array_search($collection->getId() + 200000000, $objects, true))
        {
          $objects[$key] = $collection;
        }
      }
    }
    if (!empty($collector_ids))
    {
      /** @var $collectors Collector[] */
      $collectors = CollectorQuery::create()->filterById($collector_ids, Criteria::IN)->find();

      foreach ($collectors as $collector)
      {
        if (false !== $key = array_search($collector->getId() + 300000000, $objects, true))
        {
          $objects[$key] = $collector;
        }
      }
    }
    if (!empty($collectible_ids))
    {
      /** @var $collectibles Collectible[] */
      $collectibles = CollectibleQuery::create()->filterById($collectible_ids, Criteria::IN)->find();

      foreach ($collectibles as $collectible)
      {
        if (false !== $key = array_search($collectible->getId() + 400000000, $objects, true))
        {
          $objects[$key] = $collectible;
        }
      }
    }

    // Make sure all objects are BaseObjects
    foreach ($objects as $key => $object)
    if (!$object instanceof BaseObject)
    {
      unset($objects[$key]);
    }

    return $objects;
  }

  /**
   * @param  int     $limit
   * @param  string  $culture
   *
   * @return array
   */
  public function getAttributes($limit = 0, $culture = null)
  {
    return array();
  }

  /**
   * @return array
   */
  public function getAdvertIds()
  {
    return $this->advert_ids;
  }

  /**
   * @return array
   */
  public function getAlternatives()
  {
    $alternatives = array();

    /**
     * Go through each word and add it to the alternatives
     * if there are any adverts matched
     */
    foreach ($this->words as $word => $data)
    {
      if (in_array(substr($word, 0, 1), array('=')) && $data['docs'] > 0)
      {
        $word = trim($word, '=*');

        // Querying the Sphinx search engine for the total number of docs for $word
        $total = self::search(
          array_merge($this->query, array('q' => $word)), 'total'
        );

        if ($total > 0)
        {
          $alternatives[$word] = $total;
        }
      }
    }

    return $alternatives;
  }

  /**
   * @param  integer  $v
   */
  public function setOffset($v)
  {
    $this->offset = ((int) $v < 0) ? 0 : (int) $v;
  }

  /**
   * @return integer
   */
  public function getOffset()
  {
    if ($this->offset === null)
    {
      $offset = ($this->getPage() - 1) * $this->getMaxPerPage();
      $this->setOffset($offset);
    }

    return $this->offset;
  }

  /**
   * @param  array   $query
   * @param  string  $return
   *
   * @return mixed
   */
  static public function search($query, $return = 'pks')
  {
    $sphinx = self::getSphinxClient();

    // http://www.sphinxsearch.com/docs/current.html#api-func-setlimits
    if (!empty($query['limits']) && count($query['limits']) == 2)
    {
      $sphinx->setLimits($query['limits'][0], $query['limits'][1]);
    }
    else
    {
      $sphinx->setLimits(0, ($return == 'total') ? 1 : 1000);
    }

    $q = (isset($query['q'])) ? strip_tags($query['q']) : null;
    $q = str_replace(array('+', '\\', '/', '!', '(', ')', '  '), ' ', $q);
    $q = $sphinx->escapeString(trim($q));

    // http://www.sphinxsearch.com/docs/current.html#api-func-setsortmode
    switch (@$query['sortby'])
    {
      case 'date':
        $sphinx->setSortMode(
          SPH_SORT_EXTENDED,
          sprintf('is_vip DESC, updated_at %s, @relevance DESC', isset($query['order']) ? $query['order'] : 'DESC')
        );
        break;
      case 'relevance':
      default:
        // http://sphinxsearch.com/docs/current.html#api-func-setrankingmode
        $sphinx->setRankingMode(SPH_RANK_PROXIMITY_BM25);

        $sphinx->setSortMode(
          SPH_SORT_EXTENDED,
          sprintf('score DESC, @relevance DESC, created_at %s', isset($query['order']) ? $query['order'] : 'DESC')
        );
        break;
    }

    // http://www.sphinxsearch.com/docs/current.html#api-func-setgroupby
    if (!empty($query['groupby']))
    {
      $sphinx->setGroupBy($query['groupby'], SPH_GROUPBY_ATTR);
      $sphinx->setGroupDistinct('advert_id');
    }

    $env = defined('SF_ENV') ? SF_ENV : sfConfig::get('sf_environment');
    $env = str_replace('_debug', '', $env);
    $indexes = sprintf(
      '%1$s_collectibles_normalized, %1$s_collections_normalized,
       %1$s_collectors_normalized, %1$s_blog_normalized',
      $env
    );

    $results = $sphinx->query($q, $indexes, 3);

    if ($return == 'raw')
    {
      return $results;
    }
    else if (isset($results['total']) && $results['total'] > 0)
    {
      // The the advert pks
      $pks = array_keys($results['matches']);

      switch ($return)
      {
        case 'total':
          return (int) $results['total_found'];
          break;

        case 'matches':
          return $results['matches'];
          break;

        case 'objects':
          return array();
          break;
        case 'pks':

        default:
          return $pks;
          break;
      }
    }
    else
    {
      /**
       * No results found, return the appropriate default value
       * depending on the requested return type
       */
      switch ($return)
      {
        case 'total':
          return 0;
          break;
        default:
          return array();
          break;
      }
    }
  }

  public static function getSphinxClient()
  {
    $sphinx = cqStatic::getSphinxClient();

    // http://www.sphinxsearch.com/docs/current.html#api-func-setmatchmode
    $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);

    // http://www.sphinxsearch.com/docs/current.html#api-func-setarrayresult
    $sphinx->setArrayResult(false);

    // http://www.sphinxsearch.com/docs/current.html#api-func-setconnecttimeout
    $sphinx->setConnectTimeout(3);

    // http://www.sphinxsearch.com/docs/current.html#api-func-resetfilters
    $sphinx->resetFilters();

    return $sphinx;
  }

}
