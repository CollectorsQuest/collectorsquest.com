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
  public function executeWidgetContentCategories()
  {
    // Set the limit of Categories to show
    $this->limit = (int) $this->getVar('limit') ?: 30;

    // Set the number of columns to show
    $this->columns = (int) $this->getVar('columns') ?: 2;

    $q = ContentCategoryQuery::create()
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
      if (isset($this->category) && $this->category instanceof BaseObject)
      {
        $this->videos = $magnify->getContent()->find($this->category->getSlug(), 1, $this->limit);
      }
      else if (isset($this->collectible) && $this->collectible instanceof BaseObject)
      {
        if (!$tags = $this->collectible->getTags())
        {
          $vq = (string) $tags[array_rand($tags, 1)];
          if ($videos = $magnify->getContent()->find($vq, 1, $this->limit))
          {
            foreach ($videos as $video)
            {
              $this->videos[] = $video;
            }
          }
        }

        if (count($this->videos) < $this->limit)
        {
          $q = ContentCategoryQuery::create()
             ->joinCollection()
             ->useCollectionQuery()
               ->joinCollectionCollectible()
               ->useCollectionCollectibleQuery()
                 ->filterByCollectible($this->collectible)
               ->endUse()
             ->endUse();

          if ($content_categories = $q->find()->toKeyValue('id', 'slug'))
          {
            $vq = (string) $content_categories[array_rand($content_categories, 1)];
            if ($videos = $magnify->getContent()->find($vq, 1, $this->limit - count($this->videos)))
            {
              foreach ($videos as $video)
              {
                $this->videos[] = $video;
              }
            }
          }
        }
      }
      else if (isset($this->playlist) && is_string($this->playlist))
      {
        $this->videos = $magnify->getContent()->find(
          Utf8::slugify($this->playlist, '-', true, true), 1, $this->limit
        );
      }
      else if (!empty($this->tags))
      {
        $vq = is_array($this->tags) ?
          (string) $this->tags[array_rand($this->tags, 1)] :
          (string) $this->tags;

        $this->videos = $magnify->getContent()->find($vq, 1, $this->limit);
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

    return $this->_sidebar_if(count($this->videos) > 0);
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

    $q = wpPostQuery::create()
      ->filterByPostType('seller_spotlight')
      ->filterByPostStatus('publish')
      ->orderByPostDate(Criteria::DESC);

    /** @var $wp_post wpPost */
    if ($wp_post = $q->findOne())
    {
      $values = unserialize($wp_post->getPostMetaValue('_seller_spotlight'));

      if (isset($values['cq_collector_ids']))
      {
        $collector_ids = explode(',', (string) $values['cq_collector_ids']);
        $collector_ids = array_map('trim', $collector_ids);
        $collector_ids = array_filter($collector_ids);

        $q = CollectorQuery::create()
          ->filterById($collector_ids, Criteria::IN)
          ->filterByUserType(CollectorPeer::TYPE_SELLER)
          ->addAscendingOrderByColumn('RAND()');

        $this->collectors = $q->limit(2)->find();
      }

      $this->wp_post = $wp_post;
    }


    return $this->_sidebar_if(count($this->collectors) > 0);
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
