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
      ->filterByTreeLevel(2)
      ->joinCollectorCollection(null, Criteria::INNER_JOIN)
      ->addDescendingOrderByColumn('COUNT(collector_collection.id)')
      ->orderByName(Criteria::ASC)
      ->groupById()
      ->limit($this->limit);
    $this->categories = $q->find()->getArrayCopy();

    usort($this->categories, function($a, $b)
    {
      return strcmp($a->getName(), $b->getName());
    });

    return $this->_sidebar_if(count($this->categories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetContentSubCategories()
  {
    $this->current_category = $this->getVar('current_category');
    $this->current_sub_category = new ContentCategory();
    $this->current_sub_subcategory = new ContentCategory();

    $changed_current_category = false;

    if ($this->current_category->getLevel() == 3)
    {
      $this->current_sub_subcategory = $this->current_category;
      $this->current_sub_category = $this->current_category->getParent();
      $this->current_category = $this->current_category->getParent()->getParent();
      $changed_current_category = true;
    }
    else if ($this->current_category->getLevel() == 2)
    {
      $this->current_sub_category = $this->current_category;
      $this->current_category = $this->current_category->getParent();
      $changed_current_category = true;
    }

    $this->subcategories = ContentCategoryQuery::create()
      ->childrenOf($this->current_category)
      ->hasCollections()
      ->orderBy('Name')
      ->find();

    if ($changed_current_category)
    $this->sub_subcategories = ContentCategoryQuery::create()
      ->childrenOf($this->current_sub_category)
      ->hasCollections()
      ->find();

    return $this->_sidebar_if(count($this->subcategories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetMarketplaceExplore()
  {
    // Set the limit of Collections to show
    $this->limit = (int) $this->getVar('limit') ?: 30;

    // Set the number of columns to show
    $this->columns = (int) $this->getVar('columns') ?: 2;

    /** @var $q CollectionCategoryQuery */
    $q = ContentCategoryQuery::create()
      ->filterByName('None', Criteria::NOT_EQUAL)
      ->filterByLevel(array(1, 2))
      ->hasCollectiblesForSale()
      ->orderBy('Name', Criteria::ASC)
      ->distinct();

    $this->categories = $q->find();

    return $this->_sidebar_if(count($this->categories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetMarketplaceCategories()
  {
    $this->current_category = $this->getVar('current_category');
    $this->current_sub_category = new ContentCategory();
    $this->current_sub_subcategory = new ContentCategory();
    $this->current_sub_sub_subcategory = new ContentCategory();
    $this->sub_sub_subcategories = array();

    $changed_current_category = false;
    $changed_current_category_more_levels = false;
    if ($this->current_category->getLevel() == 4)
    {
      $this->current_sub_sub_subcategory = $this->current_category;
      $this->current_sub_subcategory = $this->current_category->getParent();
      $this->current_sub_category = $this->current_category->getParent()->getParent();
      $this->current_category = $this->current_category->getParent()->getParent()->getParent();
      $changed_current_category = true;
      $changed_current_category_more_levels = true;
    }
    else if ($this->current_category->getLevel() == 3)
    {
      $this->current_sub_subcategory = $this->current_category;
      $this->current_sub_category = $this->current_category->getParent();
      $this->current_category = $this->current_category->getParent()->getParent();
      $changed_current_category = true;
      $changed_current_category_more_levels = true;
    }
    else if ($this->current_category->getLevel() == 2)
    {
      $this->current_sub_category = $this->current_category;
      $this->current_category = $this->current_category->getParent();
      $changed_current_category = true;
    }

    $this->subcategories = ContentCategoryQuery::create()
      ->descendantsOf($this->current_category)
      ->hasCollectiblesForSale()
      ->filterByLevel(2)
      ->orderBy('Name', Criteria::ASC)
      ->find();

    /*
    * logic of $this->subcategories query should be changed so all subcategories with
    * sub_subcategories that have items for sale should be visible. Currently adding the
    * current_sub_category by hand if it doesn't exist in list
    */

    $missing_category = true;
    foreach ($this->subcategories as $subcateogry)
    {
      if ($subcateogry == $this->current_sub_category)
      {
        $missing_category = false;
      }
    }

    if ($missing_category)
    {
      $this->subcategories[] = $this->current_sub_category;
    }

    if ($changed_current_category)
    {
      $this->sub_subcategories = ContentCategoryQuery::create()
        ->descendantsOf($this->current_sub_category)
        ->hasCollectiblesForSale()
        ->filterByLevel(3)
        ->orderBy('Name', Criteria::ASC)
        ->find();

      /*
       * logic of $this->sub_subcategories query should be changed so all sub_subcategories with
       * sub_sub_subcategories that have items for sale should be visible. Currently adding the
       * current_sub_subcategory by hand if it doesn't exist in list
       */

      $missing_category = true;
      foreach ($this->sub_subcategories as $sub_subcateogry)
      {
        if ($sub_subcateogry == $this->current_sub_subcategory)
        {
            $missing_category = false;
        }
      }

      if ($missing_category)
      {
        $this->sub_subcategories[] = $this->current_sub_subcategory;
      }
    }

    if ($changed_current_category_more_levels)
    $this->sub_sub_subcategories = ContentCategoryQuery::create()
      ->descendantsOf($this->current_sub_subcategory)
      ->hasCollectiblesForSale()
      ->orderBy('Name', Criteria::ASC)
      ->find();

    return $this->_sidebar_if(count($this->subcategories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetCollections()
  {
    $this->collections = $this->getVar('collections') ?: array();

    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') ?: 5;

    /** @var $height stdClass */
    if ($height = $this->getVar('height'))
    {
      $this->limit = min(floor(($height->value - 63) / 66), $this->limit);
    }

    /** @var $q CollectorCollectionQuery */
    $q = CollectorCollectionQuery::create()
      ->filterByNumItems(3, Criteria::GREATER_EQUAL);

    /** @var $collection CollectorCollection */
    if (($collection = $this->getVar('collection')) && $collection instanceof CollectorCollection)
    {
      /** @var $tags array */
      $tags = $collection->getTags();

      /** @var $content_category_id integer */
      $content_category_id = $collection->getContentCategoryId();

      /** @var $category ContentCategory */
      if ($category = $collection->getContentCategory())
      {
        // We need broader limit by category so let's get the parent at level 1
        $category = $category->getAncestorAtLevel(1) ?: $category;

        $q->filterByContentCategoryWithDescendants($category);
      }

      $q
        ->filterByTags($tags)
        ->_or()
        ->filterByContentCategoryId($content_category_id)
        ->filterById($collection->getId(), Criteria::NOT_EQUAL)
        ->orderByUpdatedAt(Criteria::DESC);
    }
    /** @var $collectible Collectible */
    else if (
      ($collectible = $this->getVar('collectible')) &&
      ($collectible instanceof Collectible || $collectible instanceof CollectionCollectible)
    )
    {
      /** @var $collection CollectoCollection */
      $collection = $collectible->getCollectorCollection();

      $collectible_tags = $collectible->getTags();
      $collection_tags = $collection->getTags();

      // See if we can get common tags between the collectible and the collection and use those
      $tags = array_intersect($collectible_tags, $collection_tags) ?: $collectible_tags;

      /** @var $category ContentCategory */
      if ($category = $collection->getContentCategory())
      {
        // We need broader limit by category so let's get the parent at level 1
        $category = $category->getAncestorAtLevel(1) ?: $category;

        $q->filterByContentCategoryWithDescendants($category);
      }

      $q
        ->filterById($collection->getId(), Criteria::NOT_EQUAL)
        ->filterByTags($tags)
        ->orderByUpdatedAt(Criteria::DESC);
    }
    else
    {
      $q
        ->filterByNumViews(1000, Criteria::GREATER_EQUAL)
        ->addAscendingOrderByColumn('RAND()');
    }

    // Make the actual query and get the Collections
    $this->collections = $q->limit($this->limit)->find();

    if (count($this->collections) === 0 && $this->getVar('fallback') === 'random')
    {
      // Get some random collections
      $c = new Criteria();
      $c->add(CollectorCollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
      $c->add(CollectorCollectionPeer::NUM_VIEWS, 1000, Criteria::GREATER_EQUAL);

      $this->collections = CollectorCollectionPeer::getRandomCollections($this->limit, $c);
    }

    // Temporary variable to avoid calling count() multiple times
    $total = count($this->collections);

    return $this->_sidebar_if(
      $total > 0 && (!empty($height) ? $height->value >= ($total * 66 + 63) : true)
    );
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

    // Temporary variable to avoid calling count() multiple times
    $total = count($this->tags);

    // Approximately how many rows of tags we have displayed
    $this->tag_rows = (integer) ($total / 4 + 1);

    return $this->_sidebar_if($total > 0);
  }

  /**
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
        $slug = $this->category->getSlug();
        if (false !== strpos($this->category->getName(), '&'))
        {
          //FIXME: Remove when slugs are updated
          $slug = Utf8::slugify($this->category->getName(), '-', true);
        }
        $this->videos = $magnify->getContent()->find($slug, 1, $this->limit);
      }
      else if (isset($this->collectible) && $this->collectible instanceof BaseObject)
      {
        // Get the actual Collectible if we are dealing with CollectionCollectible
        if ($this->collectible instanceof CollectionCollectible)
        {
          $this->collectible = $this->collectible->getCollectible();
        }

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

    $this->title = $this->getVar('title') ?: 'About '. $collector->getDisplayName();

    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') !== null ? (int) $this->getVar('limit') : 3;

    // setup PM form
    $subject = null;
    if (isset($this->collectible))
    {
      $subject = 'Regarding your item: '. addslashes($this->collectible->getName());
    }
    else if (isset($this->collection))
    {
      $subject = 'Regarding your collection: '. addslashes($this->collection->getName());
    }

    $this->pm_form = new ComposeAbridgedPrivateMessageForm(
      $this->getUser()->getCollector(), $this->getVar('collector'), $subject, array(
          'attach' => array(
              isset($this->collectible) ? $this->collectible->getCollectible() : null,
              isset($this->collection)  ? $this->collection : null,
          ),
      )
    );

    if ($collector instanceof Collector)
    {
      if ($this->limit > 0)
      {
        $c = new Criteria();
        $c->addDescendingOrderByColumn(CollectorCollectionPeer::CREATED_AT);
        $c->add(CollectorCollectionPeer::NUM_ITEMS, 0, Criteria::GREATER_THAN);
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
      ->orderByPostDate(Criteria::DESC);

    if (sfConfig::get('sf_environment') === 'prod')
    {
      $q->filterByPostStatus('publish');
    }

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
    $this->title = $this->getVar('title') ?: 'Items for Sale';

    // Set the limit of Collectibles For Sale to show
    $this->limit = (int) $this->getVar('limit') ?: 3;

    /** @var $height stdClass */
    if ($height = $this->getVar('height'))
    {
      $this->limit = min(floor(($height->value - 63) / 85), $this->limit);
    }

    // We want to stop right here if we are not going to show anything (0 items)
    if ($this->limit <= 0)
    {
      return sfView::NONE;
    }

    /** @var $q CollectibleForSaleQuery */
    $q = CollectibleForSaleQuery::create()
      ->isForSale()
      ->orderByUpdatedAt(Criteria::DESC);

    // See if we need to filter by CollectibleId first
    if (!empty($this->ids) && is_array($this->ids))
    {
      $q
        ->filterByCollectibleId($this->ids, Criteria::IN)
        ->addAscendingOrderByColumn(
          'FIELD(collectible_id, ' . implode(',', $this->ids) . ')'
        );
    }

    /** @var $wp_post wpPost */
    if (($wp_post = $this->getVar('wp_post')) && $wp_post instanceof wpPost)
    {
      $tags = $wp_post->getTags('array');
      $q->filterByTags($tags, Criteria::IN);
    }
    /** @var $wp_user wpUser */
    else if (($wp_user = $this->getVar('wp_user')) && $wp_user instanceof wpUser)
    {
      $tags = $wp_user->getTags('array');
      $q->filterByTags($tags, Criteria::IN);
    }

    /** @var $category ContentCategory */
    if (($category = $this->getVar('category')) && $category instanceof ContentCategory)
    {
      $q->filterByContentCategoryWithDescendants($category);
    }

    /** @var $collection Collection */
    if (($collection = $this->getVar('collection')) && $collection instanceof CollectorCollection)
    {
      /** @var $tags array */
      $tags = $collection->getTags();

      /** @var $category ContentCategory */
      if ($category = $collection->getContentCategory())
      {
        // We need broader limit by category so let's get the parent at level 1
        $category = $category->getAncestorAtLevel(1) ?: $category;

        $q->filterByContentCategoryWithDescendants($category);
      }

      $q
        ->filterByCollection($collection, Criteria::NOT_EQUAL)
        ->filterByTags($tags, Criteria::IN);
    }
    /** @var $collectible Collectible */
    else if (($collectible = $this->getVar('collectible')) && $collectible instanceof Collectible)
    {
      $tags = $collectible->getTags();
      $q
        ->filterByCollectible($collectible, Criteria::NOT_EQUAL)
        ->filterByTags($tags, Criteria::IN);
    }
    /** @var $collectible CollectionCollectible */
    else if (($collectible = $this->getVar('collectible')) && $collectible instanceof CollectionCollectible)
    {
      /** @var $collection CollectorCollection */
      $collection = $collectible->getCollectorCollection();

      $collectible_tags = $collectible->getTags();
      $collection_tags = $collection->getTags();

      // See if we can get common tags between the collectible and the collection and use those
      $tags = array_intersect($collectible_tags, $collection_tags) ?: $collectible_tags;

      /** @var $category ContentCategory */
      if ($category = $collection->getContentCategory())
      {
        // We need broader limit by category so let's get the parent at level 1
        $category = $category->getAncestorAtLevel(1) ?: $category;

        $q->filterByContentCategoryWithDescendants($category);
      }

      $q
        ->filterByCollectionCollectible($collectible, Criteria::NOT_EQUAL)
        ->filterByTags($tags, Criteria::IN);
    }

    // Make the actual query and get the CollectiblesForSale
    $this->collectibles_for_sale = $q->limit($this->limit)->find();

    if (count($this->collectibles_for_sale) === 0 && $this->getVar('fallback') === 'random')
    {
      /* @var $q CollectibleForSaleQuery */
      $q = CollectibleForSaleQuery::create()
        ->hasActiveCredit()
        ->isForSale()
        ->addAscendingOrderByColumn('RAND()');

      /** @var $category ContentCategory */
      if (isset($category) && $category instanceof ContentCategory)
      {
        $a = clone $q;
        if ($a->filterByContentCategoryWithDescendants($category)->count() >= $this->limit)
        {
          $q->filterByContentCategoryWithDescendants($category);
        }
      }

      $this->collectibles_for_sale = $q->limit($this->limit)->find();
    }

    return $this->_sidebar_if(count($this->collectibles_for_sale) > 0);
  }

  public function executeWidgetBlogPosts()
  {
    $this->title = $this->getVar('title') ?: 'In Other News';

    // Set the limit of other Collections to show
    $this->limit = (int) $this->getVar('limit') ?: 3;

    /** @var $height stdClass */
    if ($height = $this->getVar('height'))
    {
      $this->limit = min(floor(($height->value - 63) / 120), $this->limit);
    }

    /** @var $q wpPostQuery */
    $q = wpPostQuery::create()
      ->filterByPostType('post')
      ->filterByPostStatus('publish')
      ->orderByPostDate(Criteria::DESC)
      ->limit($this->limit);

    if (!empty($this->ids) && is_array($this->ids))
    {
      $q
        ->filterById($this->ids, Criteria::IN)
        ->addAscendingOrderByColumn(
          'FIELD(id, ' . implode(',', $this->ids) . ')'
        );
    }

    $this->wp_posts = $q->find();

    // Temporary variable to avoid calling count() multiple times
    $total = count($this->wp_posts);

    return $this->_sidebar_if(
      $total > 0 && (!empty($height) ? $height->value >= ($total * 120 + 63) : true)
    );
  }

  public function executeWidgetCollectionCollectibles(sfWebRequest $request)
  {
    /** @var $collectible Collectible|CollectionCollectible */
    $collectible = $this->getVar('collectible') ?: null;

    /** @var $collection CollectorCollection */
    if (!$collection = $this->getVar('collection') ?: null)
    {
      if ($collectible)
      {
        $collection = $collectible->getCollectible()->getCollectorCollection();
      }
      else if ($request->getParameter('collection_id'))
      {
        $collection = CollectorCollectionQuery::create()
          ->findOneById($request->getParameter('collection_id'));
      }
    }

    // Stop right here if there are not Collection OR Collectible specified
    if (!$collection && !$collectible)
    {
      return sfView::NONE;
    }

    /** @var $limit integer */
    $limit = (integer) $this->getVar('limit') ?: (integer) $request->getParameter('per_page', 3);
    $page = $collectible ? (integer) ceil($collectible->getPosition() / $limit) : $limit;
    $page = $this->getRequest()->getParameter('p', $page);

    $q = CollectionCollectibleQuery::create();
    $q->joinWith('Collectible')
      ->orderBy('Position', Criteria::ASC);

    // Filter by Collection if specified
    if ($collection)
    {
      $q->filterByCollection($collection);
    }

    $a = clone $q;

    $pager = new PropelModelPager($q, $limit);
    $pager->setPage($page);
    $pager->init();

    // NOTE: Here we have to assume that we show 3 collectibles per "page"
    if ($collectible && $collectible->getPosition() % 3 != 2)
    {
      $position = $collectible->getPosition();
      $this->collectibles = $a
        ->filterByPosition(array($position > 1 ? $position - 1 : 3, $position, $position + 1), Criteria::IN)
        ->find();
    }
    else
    {
      $this->collectibles = $pager->getResults();
    }

    $this->pager = $pager;
    $this->collection = $collection;
    $this->collectible = $collectible;

    return $this->_sidebar_if($pager->getNbResults() > 1);

  }

  public function executeWidgetMoreHistory()
  {
    return sfView::SUCCESS;
  }

  public function executeWidgetManageCollection()
  {
    $collection = $this->getVar('collection');

    return $this->_sidebar_if($this->getCollector()->isOwnerOf($collection));
  }

  public function executeWidgetManageCollectible()
  {
    $collectible = $this->getVar('collectible');

    return $this->_sidebar_if($this->getCollector()->isOwnerOf($collectible));
  }

  public function executeWidgetCollectibleBuy()
  {
    /** @var $collectible Collectible|CollectionCollectible */
    $collectible = $this->getVar('collectible');

    /** @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = null;

    if ($collectible && $collectible->isWasForSale())
    {
      /* @var $collectible_for_sale CollectibleForSale */
      $collectible_for_sale = $collectible->getCollectibleForSale();

      $this->collectible_for_sale = $collectible_for_sale;
      $this->form = new CollectibleForSaleBuyForm($collectible_for_sale);
    }

    return $this->_sidebar_if($collectible_for_sale instanceof CollectibleForSale);
  }

  public function executeWidgetMailChimpSubscribe()
  {
    return sfView::SUCCESS;
  }

  private function _sidebar_if($condition = false)
  {
    if ($condition)
    {
      return sfView::SUCCESS;
    }
    else if (
      $this->fallback && is_string($this->fallback) &&
      method_exists($this, 'execute' . $this->fallback)
    )
    {
      echo get_component('_sidebar', $this->fallback, $this->getVarHolder()->getAll());
    }
    else if (
      $this->fallback && count($this->fallback) === 2 &&
      function_exists($this->fallback[0])
    )
    {
      echo call_user_func_array($this->fallback[0], $this->fallback[1]);
    }

    return sfView::NONE;
  }

}
