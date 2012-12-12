<?php

class _sidebarComponents extends cqFrontendComponents
{
  /**
   * @return string
   */
  public function executeWidgetFacebookLikeBox()
  {
    /** @var $height stdClass */
    $height = $this->getVar('height') ?: new stdClass();

    return $this->_sidebar_if(!property_exists($height, 'value') || $height->value >= 340);
  }

  /**
   * @return string
   */
  public function executeWidgetFacebookRecommendations()
  {
    /** @var $height stdClass */
    $height = $this->getVar('height') ?: new stdClass();

    return $this->_sidebar_if(!property_exists($height, 'value') || $height->value >= 370);
  }

  /**
   * @return string
   */
  public function executeWidgetCollectionCategories()
  {
    // Set the limit of Categories to show
    $this->limit = (int) $this->getVar('limit') ?: 30;

    // Set the level of Categories to show
    $level = (int) $this->getVar('level') ?: 2;

    /** @var $q ContentCategoryQuery */
    $q = ContentCategoryQuery::create()
      ->filterByTreeLevel($level)
      ->hasCollectionsWithCollectibles()
      ->orderBy('Name', Criteria::ASC)
      ->groupById()
      ->limit($this->limit);
    $this->categories = $q->find()->getArrayCopy();

    usort($this->categories, function($a, $b)
    {
      /**
       * @var $a ContentCategory
       * @var $b ContentCategory
       */
      return strcmp($a->getName(), $b->getName());
    });

    return $this->_sidebar_if(count($this->categories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetCollectionSubCategories()
  {
    $this->current_category = $this->getVar('current_category');

    // initialize as new ContentCategory so we can check in template if value was assigned
    $this->current_sub_category = new ContentCategory();
    $this->current_sub_subcategory = new ContentCategory();
    $this->sub_subcategories = array();

    // if current_category is level > 1 we should retrieve sub_subcategories
    $retrieve_sub_subcategories = false;

    switch ($this->current_category->getLevel())
    {
      case 3:
        $this->current_sub_subcategory = $this->current_category;
        $this->current_sub_category = $this->current_category->getParent();
        $this->current_category = $this->current_category->getParent()->getParent();
        $retrieve_sub_subcategories = true;
        break;
      case 2:
        $this->current_sub_category = $this->current_category;
        $this->current_category = $this->current_category->getParent();
        $retrieve_sub_subcategories = true;
        break;
    }

    $this->subcategories = ContentCategoryQuery::create()
      ->childrenOf($this->current_category)
      ->hasCollectionsWithCollectibles()
      ->orderBy('Name')
      ->find();

    if ($retrieve_sub_subcategories)
    {
      $this->sub_subcategories = ContentCategoryQuery::create()
        ->childrenOf($this->current_sub_category)
        ->hasCollectionsWithCollectibles()
        ->find();
    }

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
      ->filterByNumCollectiblesForSale(3, Criteria::GREATER_EQUAL)
      ->orderBy('Name', Criteria::ASC);
    $this->categories = $q->find();

    return $this->_sidebar_if(count($this->categories) > 0);
  }

  /**
   * @return string
   */
  public function executeWidgetMarketplaceCategories()
  {
    $this->current_category = $this->getVar('current_category');

    $this->widget_title = $this->current_category->getName();

    // initialize as new ContentCategory so we can check in template if value was assigned
    $this->current_subcategory = new ContentCategory();

    if ($this->current_category->getLevel() == 2)
    {
      // we want to make the current category display as subcategory
      $this->current_subcategory = $this->current_category;

      // we want display category parent as widget title
      $this->current_category = $this->current_category->getParent();

      if ($this->current_category->getLevel() != 0 )
      {
        $this->widget_title = $this->current_category->getName();
      }
      // this means we have a level 2 category with parent level 0 (Root)
      else
      {
        $this->widget_title = 'Marketplace categories';
      }
    }

    /** @var $q ContentCategoryQuery */
    $q = ContentCategoryQuery::create()
      ->descendantsOf($this->current_category)
      ->hasCollectiblesForSale()
      ->orderBy('Name', Criteria::ASC);

    if ($this->current_category->getLevel() == 0)
    {
      $q->filterByLevel(array(1, 2));
    }
    else
    {
      $q->filterByLevel(2);
    }

    $this->subcategories = $q->find();

    return $this->_sidebar_if(count($this->subcategories) > 1);
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

    // We want to stop right here if we are not going to show anything (0 items)
    if ($this->limit <= 0)
    {
      return sfView::NONE;
    }

    /** @var $q FrontendCollectorCollectionQuery */
    $q = FrontendCollectorCollectionQuery::create()
      ->filterByNumItems(3, Criteria::GREATER_EQUAL);

    /** @var $collection CollectorCollection */
    if (($collection = $this->getVar('collection')) && $collection instanceof CollectorCollection)
    {
      /** @var $tags array */
      $tags = $collection->getTags();

      /** @var $content_category_id integer */
      $content_category_id = $collection->getContentCategoryId();

      $machine_tags = $collection->getTags(
        array(
          'is_triple' => true,
          'namespace' => 'matching',
          'key' => array('collections', 'all'),
          'return' => 'value'
        )
      );

      if (!empty($machine_tags))
      {
        /**
         * match machine tags against machine tags
         * @var $machine_query FrontendCollectorCollectionQuery
         */
        $machine_query = clone $q;

        $machine_query
          ->filterByMachineTags($machine_tags, 'matching', array('collections', 'all'))
          ->orderByMachineTags ($machine_tags, 'matching', array('collections', 'all'));

        /**
         * match machine tags against regular tags
         * @var $tag_query FrontendCollectorCollectionQuery
         */
        $tag_query = clone $q;

        $tag_query->filterByTags($machine_tags, Criteria::IN);
      }

      $q->filterByTags($tags, Criteria::IN)
        ->_or()
        ->filterByContentCategoryId($content_category_id)
        ->filterById($collection->getId(), Criteria::NOT_EQUAL)
        ->orderByUpdatedAt(Criteria::DESC);

      /** @var $category ContentCategory */
      if ($category = $collection->getContentCategory())
      {
        // We need broader limit by category so let's get the parent at level 1
        $category = $category->getAncestorAtLevel(1) ?: $category;

        $q->filterByContentCategoryWithDescendants($category);
      }
    }
    /** @var $collectible Collectible */
    else if (
      ($collectible = $this->getVar('collectible')) &&
      ($collectible instanceof Collectible || $collectible instanceof CollectionCollectible)
    )
    {
      /** @var  $collectible  CollectionCollectible */
      $collection = $collectible->getCollectorCollection();

      $collectible_tags = $collectible->getTags();
      $collection_tags = $collection->getTags();

      // See if we can get common tags between the collectible and the collection and use those
      $tags = array_intersect($collectible_tags, $collection_tags) ?: $collectible_tags;

      $q->filterById($collection->getId(), Criteria::NOT_EQUAL)
        ->orderByUpdatedAt(Criteria::DESC);

      $machine_tags = $collectible->getTags(
        array(
          'is_triple' => true,
          'namespace' => 'matching',
          'key' => array('collections', 'all'),
          'return' => 'value'
        )
      );

      if (!empty($machine_tags))
      {
        /**
         * match machine tags against machine tags
         * @var $machine_query FrontendCollectorCollectionQuery
         */
        $machine_query = clone $q;

        /**
         * match machine tags against regular tags
         * @var $tag_query FrontendCollectorCollectionQuery
         */
        $tag_query = clone $q;

        $machine_query
          ->filterByMachineTags($machine_tags, 'matching', array('collections', 'all'))
          ->orderByMachineTags ($machine_tags, 'matching', array('collections', 'all'));

        $tag_query->filterByTags($machine_tags, Criteria::IN);
      }


      $q->filterByTags($tags, Criteria::IN);

      /** @var $category ContentCategory */
      if ($category = $collection->getContentCategory())
      {
        // We need broader limit by category so let's get the parent at level 1
        $category = $category->getAncestorAtLevel(1) ?: $category;

        $q->filterByContentCategoryWithDescendants($category);
      }
    }
    else if (($category = $this->getVar('category')) && $category instanceof ContentCategory)
    {
      // We need broader limit by category so let's get the parent at level 1
      $category = $category->getAncestorAtLevel(1) ?: $category;

      $q->filterByContentCategoryWithDescendants($category);
    }
    else
    {
      $q
        ->filterByNumViews(1000, Criteria::GREATER_EQUAL)
        ->addAscendingOrderByColumn('RAND()');
    }

    // Make the actual query and get the Collections
    if (isset($machine_query))
    {
      $this->collections = $machine_query->limit($this->limit)->find();
      $count_collections = $machine_query->limit($this->limit)->count();

      if (isset($tag_query) && $count_collections < $this->limit)
      {
        // make sure we are not repeating collectibles_for_sale
        $tag_query->filterByCollection($this->collections, Criteria::NOT_IN);

        $additional_collections = $tag_query->limit($this->limit - $count_collections)->find();

        // add collections that are matching by machine tags
        $this->collections->exchangeArray(
          array_merge($this->collections->getArrayCopy(), $additional_collections->getArrayCopy())
        );

        // update the number of collections we already have so we can compare with limit
        $count_collections += $tag_query->limit($this->limit - $count_collections)->count();
      }

      if ($count_collections < $this->limit)
      {
        if (isset($tag_query))
        {
          // make sure we are not repeating collectibles_for_sale
          $q->filterByCollection($this->collections, Criteria::NOT_IN);
        }
        $additional_collections = $q->limit($this->limit - $count_collections)->find();

        // add collections that are not matching by machine tags but are matching by regular tags
        $this->collections->exchangeArray(
          array_merge($this->collections->getArrayCopy(), $additional_collections->getArrayCopy())
        );
      }
    }
    else
    {
      $this->collections = $q->limit($this->limit)->find();
    }

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

    /** @var $height stdClass */
    if ($height = $this->getVar('height'))
    {
      $this->limit = min(floor(($height->value - 63) / 100), $this->limit);
    }

    // We want to stop right here if we are not going to show anything (0 items)
    if ($this->limit <= 0)
    {
      return sfView::NONE;
    }

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
        $q = FrontendCollectorCollectionQuery::create()
          ->orderBy('CreatedAt', Criteria::DESC)
          ->limit($this->limit)
          ->filterBy('CollectorId', $collector->getId());

        /** @var $collection CollectorCollection */
        $collection = $this->getVar('collection');
        if ($collection instanceof BaseObject)
        {
          $q->filterBy('Id', $collection->getId(), CRITERIA::NOT_IN);
        }

        $this->collections = $q->find();
      }

      return sfView::SUCCESS;
    }
    else if ($this->fallback && method_exists($this, 'execute'.$this->fallback))
    {
      echo get_component('_sidebar', $this->fallback, $this->getVarHolder()->getAll());
    }

    return sfView::NONE;
  }

  public function executeWidgetCollectorAmericanPickers()
  {
    /** @var $collector Collector */
    $collector = $this->getVar('collector');

    $this->title = $this->getVar('title') ?: 'American Pickers on HISTORY';

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
      return sfView::SUCCESS;
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

    /** @var $height stdClass */
    if ($height = $this->getVar('height'))
    {
      $this->limit = min(floor(($height->value - 63) / 161), $this->limit);
    }

    // We want to stop right here if we are not going to show anything (0 items)
    if ($this->limit <= 0)
    {
      return sfView::NONE;
    }

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
      $values = $wp_post->getPostMetaValue('_seller_spotlight');

      if (isset($values['cq_collector_ids']))
      {
        $collector_ids = cqFunctions::explode(',', $values['cq_collector_ids']);

        /** @var $q FrontendCollectorQuery */
        $q = FrontendCollectorQuery::create()
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
    $this->title = $this->getVar('title') ?: 'From the Market';

    // Should we display only certain Collectibles?
    $this->ids = $this->getVar('ids', array());

    // Should we exclude any Collectibles?
    $this->exclude_ids = $this->getVar('exclude_ids', array());

    // Set the limit of Collectibles For Sale to show
    $this->limit = (int) $this->getVar('limit') ?: 4;

    /** @var $height stdClass */
    if ($height = $this->getVar('height'))
    {
      // one row is 142px in height, fits 2 collectibles
      $this->limit = min(floor(($height->value - 63) / 142 * round($this->limit / 2)), $this->limit);
    }

    // We want to stop right here if we are not going to show anything (0 items)
    if ($this->limit <= 0)
    {
      return sfView::NONE;
    }

    // Ignore Collectible IDs already displayed in slot1SoldCollectibleRelated component
    if ($exclude_collectible_ids = $this->getVar('exclude_collectible_ids', array()))
    {
      $this->exclude_ids = array_merge($this->exclude_ids, $exclude_collectible_ids);
    }

    /** @var $q FrontendCollectibleForSaleQuery */
    $q = FrontendCollectibleForSaleQuery::create()
      ->isForSale();

    // have random  Items on Frank's Picks pages
    $aetn_shows = sfConfig::get('app_aetn_shows', array());

    if ($this->getVar('collector') && $this->collector->getId() == $aetn_shows['american_pickers']['collector'])
    {
      $q->addAscendingOrderByColumn('RAND()');
    }
    else
    {
      $q->orderBy('UpdatedAt', Criteria::DESC);
    }

    /** @var $collection Collection */
    if (($collection = $this->getVar('collection')) && $collection instanceof CollectorCollection)
    {
      /** @var $tags array */
      $tags = $collection->getTags();

      $q->filterByCollection($collection, Criteria::NOT_EQUAL);

      $machine_tags = $collection->getTags(
        array(
          'is_triple' => true,
          'namespace' => 'matching',
          'key' => array('market', 'all'),
          'return' => 'value'
        )
      );

      if (!empty($machine_tags))
      {
        /**
         * match machine tags against machine tags
         * @var $machine_query FrontendCollectibleForSaleQuery
         */
        $machine_query = clone $q;

        /**
         * match machine tags against regular tags
         * @var $tag_query FrontendCollectibleForSaleQuery
         */
        $tag_query = clone $q;

        $machine_query->filterByMachineTags($machine_tags, 'matching', array('market', 'all'), Criteria::IN);
        $tag_query->filterByTags($machine_tags, Criteria::IN);
      }

      $q->filterByTags($tags, Criteria::IN);

      /** @var $category ContentCategory */
      if ($category = $collection->getContentCategory())
      {
        // We need broader limit by category so let's get the parent at level 1
        $category = $category->getAncestorAtLevel(1) ?: $category;

        $q->filterByContentCategoryWithDescendants($category);
      }
    }
    /** @var $collectible Collectible */
    else if (($collectible = $this->getVar('collectible')) && $collectible instanceof Collectible)
    {
      $tags = $collectible->getTags();
      $q->filterByCollectible($collectible, Criteria::NOT_EQUAL);

      $machine_tags = $collectible->getTags(
        array(
          'is_triple' => true,
          'namespace' => 'matching',
          'key' => array('market', 'all'),
          'return' => 'value'
        )
      );

      if (!empty($machine_tags))
      {
        /**
         * match machine tags against machine tags
         * @var $machine_query FrontendCollectibleForSaleQuery
         */
        $machine_query = clone $q;

        /**
         * match machine tags against regular tags
         * @var $tag_query FrontendCollectibleForSaleQuery
         */
        $tag_query = clone $q;

        $machine_query->filterByMachineTags($machine_tags, 'matching', array('market', 'all'), Criteria::IN);
        $tag_query->filterByTags($machine_tags, Criteria::IN);
      }

      $q->filterByTags($tags, Criteria::IN);
    }
    /** @var $collectible CollectionCollectible */
    else if (($collectible = $this->getVar('collectible')) && $collectible instanceof CollectionCollectible)
    {
      /** @var $collectible CollectionCollectible */
      $collection = $collectible->getCollectorCollection();

      $collectible_tags = $collectible->getTags();
      $collection_tags = $collection->getTags();

      // See if we can get common tags between the collectible and the collection and use those
      $tags = array_intersect($collectible_tags, $collection_tags) ?: $collectible_tags;

      $q->filterByCollectionCollectible($collectible, Criteria::NOT_EQUAL);

      $machine_tags = $collectible->getTags(
        array(
          'is_triple' => true,
          'namespace' => 'matching',
          'key' => array('market', 'all'),
          'return' => 'value'
        )
      );

      if (!empty($machine_tags))
      {
        /**
         * match machine tags against machine tags
         * @var $machine_query FrontendCollectibleForSaleQuery
         */
        $machine_query = clone $q;

        /**
         * match machine tags against regular tags
         * @var $tag_query FrontendCollectibleForSaleQuery
         */
        $tag_query = clone $q;

        $machine_query->filterByMachineTags($machine_tags, 'matching', array('market', 'all'), Criteria::IN);
        $tag_query->filterByTags($machine_tags, Criteria::IN);
      }

      $q->filterByTags($tags, Criteria::IN);

      /** @var $category ContentCategory */
      if ($category = $collection->getContentCategory())
      {
        // We need broader limit by category so let's get the parent at level 1
        $category = $category->getAncestorAtLevel(1) ?: $category;

        $q->filterByContentCategoryWithDescendants($category);
      }
    }
    else if (($wp_post = $this->getVar('wp_post')) && $wp_post instanceof wpPost)
    {
      /* @var $post_meta_values array */
      $post_meta_values = $wp_post->getPostMetaValue('_featured_items');

      if ($post_meta_values && !empty($post_meta_values['cq_collectibles_for_sale_ids']))
      {
        $collectibles_for_sale_ids = cqFunctions::explode(',', $post_meta_values['cq_collectibles_for_sale_ids']);

        // Get some element of surprise
        shuffle($collectibles_for_sale_ids);

        $this->ids = array_merge((array) $this->ids, $collectibles_for_sale_ids);
      }
      else
      {
        if (!empty($post_meta_values['cq_homepage_collectible_ids']))
        {
          $collectibles_for_sale_ids = cqFunctions::explode(',', $post_meta_values['cq_homepage_collectible_ids']);
          $collectibles_for_sale_ids = array_map('intval', $collectibles_for_sale_ids);

          $this->exclude_ids = array_merge(
            (array) $this->exclude_ids,
            (array) $collectibles_for_sale_ids
          );
        }

        /** @var $wp_post wpPost */
        $tags = $wp_post->getTags('array');

        $machine_tags = $wp_post->getTags(
          array(
            'is_triple' => true,
            'namespace' => 'matching',
            'key' => array('market', 'all'),
            'return' => 'value'
          )
        );

        if (!empty($machine_tags))
        {
          /**
           * match machine tags against machine tags
           * @var $machine_query FrontendCollectibleForSaleQuery
           */
          $machine_query = clone $q;

          /**
           * match machine tags against regular tags
           * @var $tag_query FrontendCollectibleForSaleQuery
           */
          $tag_query = clone $q;

          $machine_query->filterByMachineTags($machine_tags, 'matching', array('market', 'all'), Criteria::IN);
          $tag_query->filterByTags($machine_tags, Criteria::IN);
        }

        $q->filterByTags($tags, Criteria::IN);
      }
    }
    /** @var $wp_user wpUser */
    else if (($wp_user = $this->getVar('wp_user')) && $wp_user instanceof wpUser)
    {
      $tags = $wp_user->getTags('array');

      $machine_tags = $wp_user->getTags(
        array(
          'is_triple' => true,
          'namespace' => 'matching',
          'key' => array('market', 'all'),
          'return' => 'value'
        )
      );

      if (!empty($machine_tags))
      {
        /**
         * match machine tags against machine tags
         * @var $machine_query FrontendCollectibleForSaleQuery
         */
        $machine_query = clone $q;

        /**
         * match machine tags against regular tags
         * @var $tag_query FrontendCollectibleForSaleQuery
         */
        $tag_query = clone $q;

        $machine_query->filterByMachineTags($machine_tags, 'matching', array('market', 'all'), Criteria::IN);
        $tag_query->filterByTags($machine_tags, Criteria::IN);
      }

      $q->filterByTags($tags, Criteria::IN);
    }

    if ($collector = $this->getVar('collector'))
    {
      $this->exclude_ids = array_merge($this->exclude_ids, (array) $this->getVar('exclude_collectible_ids', array()));
      if ($collector instanceof Collector)
      {
        $q->filterByCollector($collector);

        $this->collector = $collector;
      }
    }

    // Remove the IDs we want to exclude from $this->ids
    $this->ids = array_diff($this->ids, $this->exclude_ids);

    $q
      ->_if(!empty($this->ids))
        ->filterByCollectibleId($this->ids, Criteria::IN)
        ->clearOrderByColumns()
        ->addAscendingOrderByColumn(
          'FIELD(collectible_for_sale.collectible_id, ' . implode(',', $this->ids) . ')'
        )
      ->_elseif(!empty($this->exclude_ids))
        ->filterByCollectibleId($this->exclude_ids, Criteria::NOT_IN)
      ->_endif();

    /** @var $category ContentCategory */
    if (($category = $this->getVar('category')) && $category instanceof ContentCategory)
    {
      $q->filterByContentCategoryWithDescendants($category);
    }

    // Make the actual query and get the CollectiblesForSale
    if (isset($machine_query))
    {
      $this->collectibles_for_sale = $machine_query->limit($this->limit)->find();
      $count_collectibles_for_sale = $machine_query->limit($this->limit)->count();

      if (isset($tag_query) && $count_collectibles_for_sale < $this->limit)
      {
        // make sure we are not repeating collectibles_for_sale
        $collectible_ids = $this->collectibles_for_sale->toKeyValue('CollectibleId', 'CollectibleId');
        $tag_query->filterByCollectibleId(array_merge((array) $this->exclude_ids, $collectible_ids), Criteria::NOT_IN);

        $additional_collectibles_for_sale = $tag_query->limit($this->limit - $count_collectibles_for_sale)->find();
        // add collectibles_for_sale that are matching by machine tags
        $this->collectibles_for_sale->exchangeArray(
          array_merge($this->collectibles_for_sale->getArrayCopy(), $additional_collectibles_for_sale->getArrayCopy())
        );

        // update the number of collectibles_for_sale we already have so we can compare with limit
        $count_collectibles_for_sale += $tag_query->limit($this->limit - $count_collectibles_for_sale)->count();
      }

      if ($count_collectibles_for_sale < $this->limit)
      {
        // make sure we are not repeating collectibles_for_sale
        $collectible_ids = $this->collectibles_for_sale->toKeyValue('CollectibleId', 'CollectibleId');
        $q->filterByCollectibleId(array_merge((array) $this->exclude_ids, $collectible_ids), Criteria::NOT_IN);

        $additional_collectibles_for_sale = $q->limit($this->limit - $count_collectibles_for_sale)->find();
        // add collectibles_for_sale that are not matching by machine tags but are matching by regular tags
        $this->collectibles_for_sale->exchangeArray(
          array_merge($this->collectibles_for_sale->getArrayCopy(), $additional_collectibles_for_sale->getArrayCopy())
        );
      }
    }
    else
    {
      $this->collectibles_for_sale = $q->limit($this->limit)->find();
    }


    // Should be "fallback" if there are no collectibles for sale?
    if (count($this->collectibles_for_sale) === 0 && $this->getVar('fallback') === 'random')
    {
      /* @var $q CollectibleForSaleQuery */
      $q = FrontendCollectibleForSaleQuery::create()
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

    return $this->_sidebar_if($this->collectibles_for_sale->count() > 1);
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
          'FIELD(wp_posts.id, ' . implode(',', $this->ids) . ')'
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
    if (!$collection = ($this->getVar('collection') ?: null))
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

    $pager = new cqCollectionCollectiblesPager(
      $collection, (integer) $this->getVar('limit') ?: (integer) $request->getParameter('per_page', 3)
    );
    $pager->setPage($this->getRequest()->getParameter('p', 1));
    $pager->setCollectibleId($collectible ? $collectible->getId() : $request->getParameter('collectible_id'));
    $pager->init();

    $this->pager = $pager;
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
    /** @var $height stdClass */
    $height = $this->getVar('height') ?: new stdClass();

    return $this->_sidebar_if(!property_exists($height, 'value') || $height->value >= 190);
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
