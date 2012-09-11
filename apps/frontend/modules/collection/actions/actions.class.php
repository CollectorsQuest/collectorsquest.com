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
    $picked_off = sfConfig::get('app_aetn_picked_off');

    if (
      in_array($collection->getId(), array(
        $pawn_stars['collection'], $american_pickers['collection'], $picked_off['collection']
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
    $collectible_rows = count($pager->getResults());
    $collectible_rows = $collectible_rows % 3 == 0 ? intval($collectible_rows / 3) : intval($collectible_rows / 3 + 1);

    $this->collectible_rows = $collectible_rows;

    // Building the meta tags
    // $this->getResponse()->addMeta('description', $collection->getDescription('stripped'));
    // $this->getResponse()->addMeta('keywords', $collection->getTagString());

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    if ($collection->getNumItems() == 0)
    {
      $this->collections = null;

      if (!($collection instanceof CollectionDropbox) && !$this->getUser()->isOwnerOf($collection))
      {
        $this->collections = CollectorCollectionQuery::create()
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

    /**
     * Special checks for the Collectibles of A&E
     */
    $pawn_stars = sfConfig::get('app_aetn_pawn_stars');
    $american_pickers = sfConfig::get('app_aetn_american_pickers');
    $picked_off = sfConfig::get('app_aetn_picked_off');

    if (in_array($collection->getId(), array($pawn_stars['collection'], $american_pickers['collection'])))
    {
      $this->redirect('aetn_collectible_by_slug', $collectible);
    }
    else if ($collection->getId() == $picked_off['collection'])
    {
      $this->redirectIf(
        IceGateKeeper::open('aetn_picked_off', 'page'),
        'aetn_collectible_by_slug', $collectible
      );
    }

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

    return sfView::SUCCESS;
  }

  public function executeAentCollectibleFixedMatching()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();

    /** @var $collection Collection */
    $collection = $collectible->getCollectorCollection();

    /** @var $collector Collector */
    $collector = $collectible->getCollector();

    $pawn_stars = sfConfig::get('app_aetn_pawn_stars');
    $american_pickers = sfConfig::get('app_aetn_american_pickers');

    /** @var $q CollectibleQuery */
    $q = CollectionCollectibleQuery::create()
      ->filterByCollectionId($pawn_stars['collection'])
      ->orderByCollectibleId(Criteria::ASC);
    $ps_collectibles = $q->find();

    $q = CollectionCollectibleQuery::create()
      ->filterByCollectionId($american_pickers['collection'])
      ->orderByCollectibleId(Criteria::ASC);
    $ap_collectibles = $q->find();

    /**
     * Pawn Stars
     */
    if ($collectible->getId() == $ps_collectibles[0]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1288, 1044, 478, 401, 1280, 1043, 2792, 1042), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[1]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1597, 1244, 1585, 1249, 1268, 2815, 602, 1168), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[2]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1811, 1953, 1122, 375, 378, 30928, 374, 1161, 1727), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[3]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(2791, 2173, 1281, 1452, 2373, 1290, 699, 1183), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[4]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(2151, 270, 2649, 1695, 2651, 2831, 1249, 675, 1044, 1782), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[5]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1080, 866, 855, 862, 863, 949, 463, 970), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[6]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1484, 1352, 1287, 1280, 1260, 1346, 1337, 1335), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[7]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1117, 290, 1138, 1323, 1072, 1151, 2180, 2308), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[8]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1847, 545, 1290, 789, 477, 307, 1934, 823), Criteria::IN
      )->orderById('DESC')->find();
    }

    /**
     * American Pickers
     */
    else if ($collectible->getId() == $ap_collectibles[0]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(531, 891, 807, 982, 1123, 557, 1547, 838, 2888), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[1]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(761, 1284, 228, 495, 156, 11, 914, 294), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[2]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1780, 1335, 1457, 2838, 831, 1337, 1303, 1485), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[3]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1329, 1328, 852, 1573, 1048, 2713, 512, 2201), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[4]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(260, 234, 1168, 263, 51, 843, 2810, 1098, 415), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[5]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(390, 932, 465, 201, 447, 804, 708, 2117), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[6]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(619, 2844, 40, 122, 454, 894, 456, 703), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[7]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(752, 616, 807, 1576, 437, 812, 1584, 1183, 709), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[8]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(819, 618, 26, 574, 752, 1727, 1059, 910), Criteria::IN
      )->find();
    }
    /**
     * Fall back
     */
    else
    {
      $this->related_collections = CollectorCollectionPeer::getRelatedCollections($collectible, 8);
    }

    if ($collection->getId() == $american_pickers['collection'])
    {
      $this->brand = "American Pickers";
    }
    else if ($collection->getId() == $pawn_stars['collection'])
    {
      $this->brand = "Pawn Stars";
    }
    else
    {
      $this->brand = null;
    }

    /*
     * this seems redundant - please delete if true
     *
    if ($videos = $collectible->getMultimedia(1, 'video', false))
    {
      $this->video = $videos;
    }*/

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
      SmartMenu::setSelected('header_main_menu', 'marketplace');
    }

    $this->collector = $collector;
    $this->collection = $collection;
    $this->collectible = $collectible;
    $this->collectible_for_sale = $collectible->getCollectibleForSale();
    $this->additional_multimedia = $collectible->getMultimedia(0, 'image', false);
    $this->editable = $this->getUser()->isOwnerOf($collectible);

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collectible);

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_collectible_by_slug', $collectible));

    $this->setTemplate('collectible');

    return sfView::SUCCESS;
  }

  public function executeCreate()
  {
    $this->redirect('@mycq_collections');
  }

}
