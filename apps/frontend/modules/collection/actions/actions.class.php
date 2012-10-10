<?php

class collectionActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header', 'collections');
  }

  /**
   * @param sfWebRequest $request
   * @return string
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->getRoute() instanceof sfPropelRoute);

    /** @var $object BaseObject */
    $object = $this->getRoute()->getObject();

    if ($object instanceof Collector)
    {
      /** @var $collector Collector */
      $collector = $object;

      /** @var $collection CollectionDropbox */
      $collection = $collector->getCollectionDropbox();
    }
    else
    {
      /** @var $collection CollectorCollection */
      $collection = $object;

      /** @var $collector Collector */
      $collector = $collection->getCollector();
    }

    /**
     * Special checks for the Collectibles of A&E
     */
    $pawn_stars = sfConfig::get('app_aetn_pawn_stars');
    $american_pickers = sfConfig::get('app_aetn_american_pickers');
    $american_restoration = sfConfig::get('app_aetn_american_restoration');
    $picked_off = sfConfig::get('app_aetn_picked_off');

    if (
      in_array($collection->getId(), array(
        $pawn_stars['collection'], $american_pickers['collection'],
        $american_restoration['collection'], $picked_off['collection']
      ))
    )
    {
      if ($collection->getId() == $pawn_stars['collection'])
      {
        $this->redirect('@aetn_pawn_stars', 301);
      }
      else if ($collection->getId() == $american_pickers['collection'])
      {
        $this->redirect('@aetn_american_pickers', 301);
      }
      else if ($collection->getId() == $american_restoration['collection'])
      {
       $this->redirectIf(
          IceGateKeeper::open('aetn_american_restoration', 'page'),
          '@aetn_american_restoration', 301
        );
      }
      else if ($collection->getId() == $picked_off['collection'])
      {
        $this->redirectIf(
          IceGateKeeper::open('aetn_picked_off', 'page'),
          '@aetn_picked_off', 301
        );
      }
    }

    if (!$this->getCollector()->isOwnerOf($collection))
    {
      $collection->setNumViews($collection->getNumViews() + 1);
      $collection->save();
    }

    $c = new Criteria();
    $c->add(CollectiblePeer::COLLECTOR_ID, $collection->getCollectorId());
    $c->add(CollectiblePeer::IS_PUBLIC, true);

    if ($collection instanceof CollectionDropbox)
    {
      $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN);
      $c->add(CollectionCollectiblePeer::COLLECTION_ID, null, Criteria::ISNULL);
    }
    else
    {
      $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID);
      $c->add(CollectionCollectiblePeer::COLLECTION_ID, $collection->getId());
    }

    $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addAscendingOrderByColumn(CollectiblePeer::CREATED_AT);

    $per_page = sfConfig::get('app_pager_list_collectibles_max', 24);

    $pager = new cqPropelPager('CollectionCollectible', $per_page);
    $pager->setCriteria($c);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->setStrictMode('all' === $request->getParameter('show'));
    $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'collectibles');
    $this->collector = $collector;
    $this->collection = $collection;
    $this->editable = $this->getUser()->isOwnerOf($collection);

    // calculate how many rows of collectibles will be on the page
    $results_count = count($pager->getResults());
    $collectible_rows = $results_count % 3 == 0 ? intval($results_count / 3) : intval($results_count / 3 + 1);
    $this->collectible_rows = $collectible_rows;

    // if we don't have (public) Collectibles in Collection
    if ($results_count == 0)
    {
      // user is NOT owner of Collection -> should not display Collection
      if (!$this->getCollector()->isOwnerOf($collection))
      {
        $this->forward404();
      }
      // user IS owner of Collection -> display Flash to explain why Collection is not visible
      else
      {
        $this->getUser()->setFlash(
          'error',
          'Your collection will not be publicly viewable until you have publicly viewable items in it!'
        );
      }
    }

    // if Collection is not public and user is it's owner
    if ($collection->getIsPublic() === false && $this->getCollector()->isOwnerOf($collection))
    {
      $this->getUser()->setFlash(
        'error',
        sprintf(
          'Your collection will not be publicly viewable until you fill in all the required information!<br> %s',
          link_to('Edit collection', 'mycq_collection_by_section',
            array('id' => $collection->getId(), 'section' => 'details')
          )
        )
      );
    }

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    if ($collection->getNumItems() == 0)
    {
      $this->collections = null;

      if (!($collection instanceof CollectionDropbox) && !$this->getUser()->isOwnerOf($collection))
      {
        $this->collections = FrontendCollectorCollectionQuery::create()
          ->_if($collection->getCollectionCategoryId())
            ->filterByCollectionCategoryId($collection->getCollectionCategoryId())
          ->_elseif($collection->getContentCategoryId())
            ->filterByContentCategoryId($collection->getContentCategoryId())
          ->_endif()
          ->filterByNumItems(4, Criteria::GREATER_EQUAL)
          ->orderByScore()
          ->orderByCreatedAt(Criteria::DESC)
          ->limit(9)
          ->find();
      }

      return 'NoCollectibles';
    }

    return sfView::SUCCESS;
  }

  public function executeCollectible()
  {
    $this->forward404Unless($this->getRoute() instanceof sfPropelRoute);

    /** @var $collectible Collectible|CollectionCollectible */
    $collectible = $this->getRoute()->getObject();

    /** @var $collection Collection */
    $collection = $collectible->getCollection();

    /** @var $collector Collector */
    $collector = $collectible->getCollector();

    // Stop right here if we are missing any of these
    $this->forward404Unless($collectible && $collection && $collector);

    // We do not want to show Collectibles which are not assigned to a CollectorCollection
    $this->forward404Unless($collection->getId());

    /**
     * Special checks for the Collectibles of A&E Shows
     */
    $this->_aetnCollectibleFixedMatching();

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collectible))
    {
      $collectible->setNumViews($collectible->getNumViews() + 1);
      $collectible->save();
    }

    /**
     * Figure out the previous and the next item in the collection
     */
    $collectible_ids = $collection->getCollectibleIds();
    if (count($collectible_ids) > 1)
    {
      if (array_search($collectible->getId(), $collectible_ids) - 1 < 0)
      {
        $q = CollectionCollectibleQuery::create()
            ->filterByCollection($collection)
            ->filterByCollectibleId($collectible_ids[count($collectible_ids) - 1]);
        $this->previous = $q->findOne();
      }
      else
      {
        $q = CollectionCollectibleQuery::create()
            ->filterByCollection($collection)
            ->filterByCollectibleId($collectible_ids[array_search($collectible->getId(), $collectible_ids) - 1]);
        $this->previous = $q->findOne();
      }

      if (array_search($collectible->getId(), $collectible_ids) + 1 >= count($collectible_ids))
      {
        $q = CollectionCollectibleQuery::create()
            ->filterByCollection($collection)
            ->filterByCollectibleId($collectible_ids[0]);
        $this->next = $q->findOne();
      }
      else
      {
        $q = CollectionCollectibleQuery::create()
            ->filterByCollection($collection)
            ->filterByCollectibleId($collectible_ids[array_search($collectible->getId(), $collectible_ids) + 1]);
        $this->next = $q->findOne();
      }
      /**
       * Figure out the first item in the collection
       */
      $q = CollectionCollectibleQuery::create()
        ->filterByCollection($collection)
        ->filterByCollectibleId($collectible_ids[0]);
      $this->first = $q->findOne();
    }
    if ($collectible->isWasForSale())
    {
      SmartMenu::setSelected('header', 'marketplace');
    }

    $breadcrumbs = IceBreadcrumbs::getInstance($this->getContext());

    if (preg_match('#/marketplace#i', IceRequestHistory::getRequestUriFromCurrent(-1)))
    {
      $breadcrumbs->addItem('Marketplace', '@marketplace');
    }
    else
    {
      $breadcrumbs->addItem($collection->getName(), $this->getController()->genUrl(array(
        'sf_route'  => 'collection_by_slug',
        'sf_subject'=> $collection
      )));
    }
    $breadcrumbs->addItem($collectible->getName());

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collectible->getCollectible());

    $this->collector = $collector;
    $this->collection = $collection;
    $this->collectible = $collectible;
    $this->collectible_for_sale = $collectible->getCollectibleForSale();
    $this->additional_multimedia = $collectible->getMultimedia(0, 'image', false);
    $this->editable = $this->getUser()->isOwnerOf($collectible);

    if ($videos = $collectible->getMultimedia(1, 'video', false))
    {
      $this->video = $videos;
    }

    // Make the Collectible available to the sidebar
    $this->setComponentVar('collectible', $collectible, 'sidebarCollectible');

    if ($collectible->getIsPublic() === false && $this->getCollector()->isOwnerOf($collectible))
    {
      $this->getUser()->setFlash(
        'error',
        sprintf(
          'Your item will not be publicly viewable until you fill in all the required information! %s',
          link_to('Edit item', 'mycq_collectible_by_slug', $collectible)
        )
      );
    }

    return sfView::SUCCESS;
  }

  private function _aetnCollectibleFixedMatching()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();

    /** @var $collection Collection */
    if (!$collection = $collectible->getCollectorCollection())
    {
      return false;
    }

    $this->aetn_show = null;
    $aetn_shows = sfConfig::get('app_aetn_shows');

    foreach ($aetn_shows as $id => $show)
    {
      if ($collection->getId() === $show['collection'])
      {
        $this->aetn_show = $show;
        $this->aetn_show['id'] = $id;

        break;
      }
    }

    // Stop right here if not an A&E Show's collection
    if (!$this->aetn_show)
    {
      return sfView::NONE;
    }

    /** @var $q CollectionCollectibleQuery */
    $q = CollectionCollectibleQuery::create()
      ->filterByCollection($collection)
      ->filterByCollectible($collectible->getCollectible(), Criteria::NOT_EQUAL)
      ->addAscendingOrderByColumn('RAND()');

    $this->related_collectibles = $q->limit(8)->find();

    // Make the A&E show available in the sidebar
    $this->setComponentVar('aetn_show', $this->aetn_show, 'sidebarCollectible');

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl(
      'http://' . sfConfig::get('app_www_domain') .
      $this->generateUrl('aetn_collectible_by_slug', array('sf_subject' => $collectible), false)
    );

    return sfView::SUCCESS;
  }

  public function executeCreate()
  {
    $this->redirect('@mycq_collections');
  }

}
