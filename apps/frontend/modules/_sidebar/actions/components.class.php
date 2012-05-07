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
    $this->limit = (int) $this->getVar('limit') ?: 30;

    // Set the number of columns to show
    $this->columns = (int) $this->getVar('columns') ?: 2;

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
    $this->limit = (int) $this->getVar('limit') ?: 30;

    // Set the number of columns to show
    $this->columns = (int) $this->getVar('columns') ?: 2;

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
  public function executeWidgetPopularTopics()
  {
    // Set the limit of Categories to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 30;

    // Set the number of columns to show
    $this->columns = $this->getVar('columns') ? (int) $this->getVar('columns') : 2;

    $ids = array(
      2684, 2834, 3085, 3204, 3358, 3459,
      3569, 3820, 3893, 4109, 4248, 4366,
      4444, 4495, 4594, 4855, 4955, 5526,
      5735, 5736, 6068, 6159, 4903
    );

    $q = ContentCategoryQuery::create()
      ->filterById($ids, Criteria::IN)
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
    $this->limit = (int) $this->getVar('limit') ?: 30;

    // Set the number of columns to show
    $this->columns = (int) $this->getVar('columns') ?: 2;

    /** @var $q CollectionCategoryQuery */
    $q = ContentCategoryQuery::create()
      ->distinct()
      ->filterByName('None', Criteria::NOT_EQUAL)
      ->filterByTreeLevel(array(1, 2))
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
  public function executeWidgetCollections()
  {
    $this->collections = $this->getVar('collections') ?: array();

    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') ?: 5;

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
    $this->title = $this->getVar('title') ?: 'Tags';
    $this->tags = $this->getVar('tags') ?: array();

    // Set the limit of Tags to show
    $this->limit = $this->getVar('limit') ?: 0;

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
    $this->limit = (int) $this->getVar('limit') ?: 5;

    $this->tags = $this->getVar('tags') ?: array();

    $magnify = cqStatic::getMagnifyClient();
    $this->videos = array();

    try
    {
      if (isset($this->category))
      {
        $this->videos = $magnify->getContent()->find($this->category->getSlug(), 1, $this->limit);
      }
      else if (isset($this->playlist))
      {
        $this->videos = $magnify->getContent()->find(
          Utf8::slugify($this->playlist, '-', true, true), 1, $this->limit
        );
      }
      else if (!empty($this->tags))
      {
        $tags = is_array($this->tags) ? implode(' ', $this->tags) : $this->tags;
        $this->videos = $magnify->getContent()->find($tags, 1, $this->limit);
      }
      else
      {
        $this->videos = $magnify->getContent()->browse(1, $this->limit);
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

    $this->title = $this->getVar('title') ?: 'About the Collector';

    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') !== null ? (int) $this->getVar('limit') : 3;

    if ($collector instanceof Collector)
    {
      if ($this->limit > 0)
      {
        $c = new Criteria();
        $c->addDescendingOrderByColumn(CollectorCollectionPeer::CREATED_AT);
        $c->setLimit($this->limit);

        /** @var $collection Collection */
        $collection = $this->getVar('collection');
        if ($collection instanceof BaseObject)
        {
          $c->add(CollectorCollectionPeer::ID, $collection->getId(), Criteria::NOT_EQUAL);
        }

        $this->collections = $collector->getCollectorCollections($c);
      }

      return sfView::SUCCESS;
    }
    else if ($this->fallback && method_exists($this, 'execute'.$this->fallback))
    {
      echo get_component('_sidebar', $this->fallback, $this->getVarHolder()->getAll());
    }

    return sfView::NONE;
  }

  public function executeWidgetCollectorMostWanted()
  {
    /** @var $collector Collector */
    $collector = $this->getVar('collector');

    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') !== null ? (int) $this->getVar('limit') : 3;

    if ($collector instanceof Collector)
    {
      $this->title = $this->getVar('title') ?: $collector->getDisplayName() ."'s Most Wanted";

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
    $this->title = $this->getVar('title') ?: 'Featured Sellers';

    // Set the limit of other Collections to show
    $this->limit = (int) $this->getVar('limit') ?: 0;

    $q = CollectorQuery::create()
       ->filterByUserType(CollectorPeer::TYPE_SELLER)
       ->limit(2);
    $this->sellers = $q->find();

    return $this->_sidebar_if(count($this->sellers) > 0);
  }

  public function executeWidgetCollectiblesForSale()
  {
    $this->title = $this->getVar('title') ?: 'Collectibles for Sale';

    // Set the limit of Collectibles For Sale to show
    $this->limit = (int) $this->getVar('limit') ?: 0;

    $q = CollectibleForSaleQuery::create()
      ->isForSale()
      ->orderByUpdatedAt(Criteria::DESC);

    $this->collectibles_for_sale = $q->limit($this->limit)->find();

    return $this->_sidebar_if(count($this->collectibles_for_sale) > 0);
  }

  public function executeWidgetBlogPosts()
  {
    $this->title = $this->getVar('title') ?: 'In Other News';

    // Set the limit of other Collections to show
    $this->limit = (int) $this->getVar('limit') ?: 0;

    $q = wpPostQuery::create()
      ->filterByPostType('post')
      ->filterByPostStatus('publish')
      ->orderByPostDate(Criteria::DESC)
      ->limit($this->limit);
    $this->wp_posts = $q->find();

    return $this->_sidebar_if(count($this->wp_posts) > 0);
  }

  public function executeWidgetCollectionCollectibles()
  {
    // Set the limit of other Collections to show
    $this->collection = $this->getVar('collection') ?: null;

    // Set the limit of other Collections to show
    $this->limit = (int) $this->getVar('limit') ?: 4;

    if ($this->collection)
    {
      $q = CollectionCollectibleQuery::create()
        ->filterByCollection($this->collection)
        ->limit($this->limit);
      $this->collectibles = $q->find();
    }

    return $this->_sidebar_if(count($this->collectibles) > 0);
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
