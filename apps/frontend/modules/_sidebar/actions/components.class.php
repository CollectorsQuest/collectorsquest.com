<?php

class _sidebarComponents extends cqFrontendComponents
{
  /**
   * @return string
   */
  public function executeWidgetFacebookLikeBox()
  {
    return sfView::SUCCESS;
  }

  /**
   * @return string
   */
  public function executeWidgetFacebookRecommendations()
  {
    return sfView::SUCCESS;
  }

  /**
   * @return string
   */
  public function executeWidgetCollectionCategories()
  {
    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 30;

    // Set the number of columns to show
    $this->columns = $this->getVar('columns') ? (int) $this->getVar('columns') : 2;

    $q = CollectionCategoryQuery::create()
      ->filterById(0, Criteria::NOT_EQUAL)
      ->filterByParentId(0, Criteria::EQUAL)
      ->orderByName(Criteria::ASC)
      ->limit($this->limit);
    $this->categories = $q->find();

    return $this->_sidebar_if(count($this->categories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetContentCategories()
  {
    // Set the limit of Categories to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 30;

    // Set the number of columns to show
    $this->columns = $this->getVar('columns') ? (int) $this->getVar('columns') : 2;

    $q = CollectionCategoryQuery::create()
      ->filterById(0, Criteria::NOT_EQUAL)
      ->filterByParentId(0, Criteria::EQUAL)
      ->orderByName(Criteria::ASC)
      ->limit($this->limit);
    $this->categories = $q->find();

    return $this->_sidebar_if(count($this->categories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetMarketplaceCategories()
  {
    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 30;

    // Set the number of columns to show
    $this->columns = $this->getVar('columns') ? (int) $this->getVar('columns') : 2;

    /** @var $q CollectionCategoryQuery */
    $q = CollectionCategoryQuery::create()
      ->distinct()
      ->filterByName('None', Criteria::NOT_EQUAL)
      ->orderBy('Name', Criteria::ASC)
      ->joinCollection()
      ->useCollectionQuery()
        ->joinCollectionCollectible()
        ->useCollectionCollectibleQuery()
          ->joinCollectible()
          ->useCollectibleQuery()
            ->joinCollectibleForSale()
            ->useCollectibleForSaleQuery()
              ->filterByIsSold(false)
            ->endUse()
          ->endUse()
        ->endUse()
      ->endUse();
    $this->categories = $q->find();

    return $this->_sidebar_if(count($this->categories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetRelatedCollections()
  {
    $this->collections = $this->getVar('collections') ? $this->getVar('collections') : array();

    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') ? $this->getVar('limit') : 5;

    /** @var $collectible Collectible */
    if ($collectible = $this->getVar('collectible'))
    {
      $this->collections = $collectible->getRelatedCollections($this->limit);
    }
    else if (empty($this->collections))
    {
      // Get some random collections
      $c = new Criteria();
      $c->add(CollectorCollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
      $this->collections = CollectorCollectionPeer::getRandomCollections($this->limit, $c);
    }

    return $this->_sidebar_if(count($this->collections) > 0);
  }

  public function executeWidgetTags()
  {
    $this->title = $this->getVar('title') ? $this->getVar('title') : 'Tags';
    $this->tags = $this->getVar('tags') ? $this->getVar('tags') : array();

    // Set the limit of Tags to show
    $this->limit = $this->getVar('limit') ? $this->getVar('limit') : 0;

    /** @var $collection Collection */
    if ($collection = $this->getVar('collection'))
    {
      $this->tags = $collection->getTags();
    }
    /** @var $collectible Collectible */
    else if ($collectible = $this->getVar('collectible'))
    {
      $this->tags = $collectible->getTags();
    }

    return $this->_sidebar_if(count($this->tags) > 0);
  }

  /**
   * TODO: Thrown magnify errors should be handled
   *
   * @return string
   */
  public function executeWidgetMagnifyVideos()
  {
    $limit = isset($this->limit) ? (int) $this->limit : sfConfig::get('app_member_videos_per_page', 5);

    $magnify = cqStatic::getMagnifyClient();
    $this->videos = array();

    try
    {
      if (isset($this->category))
      {
        $this->videos = $magnify->getContent()->find($this->category->getName(), 1, $limit);
      }
      else if (isset($this->tags))
      {
        $tags = is_array($this->tags) ? implode(' ', $this->tags) : $this->tags;
        $this->videos = $magnify->getContent()->find($tags, 1, $limit);
      }
      else
      {
        $this->videos = $magnify->getContent()->browse(1, $limit);
      }
    }
    catch (MagnifyException $e)
    {
      return sfView::NONE;
    }

    return $this->_sidebar_if((int) $this->videos->totalResults > 0);
  }

  public function executeWidgetCollector()
  {
    /** @var $collector Collector */
    $collector = $this->getVar('collector');

    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 3;

    if (!$collector instanceof Collector)
    {
      $c = new Criteria();
      $c->addDescendingOrderByColumn(CollectorCollectionPeer::CREATED_AT);
      $c->setLimit($this->limit);
      $this->collections = $collector->getCollectorCollections($c);

      return sfView::SUCCESS;
    }
    else if ($this->fallback && method_exists($this, 'execute'.$this->fallback))
    {
      echo get_component('_sidebar', $this->fallback, $this->getVarHolder()->getAll());
    }

    return sfView::NONE;
  }

  public function executeWidgetFeaturedSellers()
  {
    // Set the limit of other Collections to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 0;

    return $this->_sidebar_if(count($this->sellers) > 0);
  }

  public function executeWidgetCollectiblesForSale()
  {
    // Set the limit of other Collections to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 0;

    $q = CollectibleForSaleQuery::create()->limit($this->limit);
    $this->collectibles_for_sale = $q->find();

    return $this->_sidebar_if(count($this->collectibles_for_sale) > 0);
  }

  public function executeWidgetBlogPosts()
  {
    // Set the limit of other Collections to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 0;

    $q = wpPostQuery::create()
      ->filterByPostType('post')
      ->filterByPostStatus('publish')
      ->orderByPostDate(Criteria::DESC)
      ->limit($this->limit);
    $this->wp_posts = $q->find();

    return $this->_sidebar_if(count($this->wp_posts) > 0);
  }

  private function _sidebar_if($condition = false)
  {
    if ($condition)
    {
      return sfView::SUCCESS;
    }
    else if ($this->fallback && method_exists($this, 'execute'.$this->fallback))
    {
      echo get_component('_sidebar', $this->fallback, $this->getVarHolder()->getAll());
    }

    return sfView::NONE;
  }

}
