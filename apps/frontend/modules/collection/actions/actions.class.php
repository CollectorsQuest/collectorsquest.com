<?php

class collectionActions extends cqFrontendActions
{
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
      $collector  = $collection->getCollector();
    }

    /**
     * Special checks for the Collectibles of A&E
     */
    $pawn_stars = sfConfig::get('app_aetn_pawn_stars');
    $american_pickers = sfConfig::get('app_aetn_american_pickers');

    if (in_array($collection->getId(), array($pawn_stars['collection'], $american_pickers['collection'])))
    {
      if ($collection->getId() == $pawn_stars['collection'])
      {
        $this->redirect('@aetn_pawn_stars', 301);
      }
      else if ($collection->getId() == $american_pickers['collection'])
      {
        $this->redirect('@aetn_american_pickers', 301);
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

    $pager = new sfPropelPager('Collectible', $per_page);
    $pager->setCriteria($c);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    $this->pager      = $pager;
    $this->display    = $this->getUser()->getAttribute('display', 'grid', 'collectibles');
    $this->collector  = $collector;
    $this->collection = $collection;

    // Building the meta tags
    $this->getResponse()->addMeta('description', $collection->getDescription('stripped'));
    $this->getResponse()->addMeta('keywords', $collection->getTagString());

    if ($collection->countCollectibles() == 0)
    {
      $this->collections = null;

      if (!($collection instanceof CollectionDropbox) && !$collector->isOwnerOf($collection))
      {
        $c = new Criteria();
        $c->add(CollectionPeer::IS_PUBLIC, true);
        if ($collection->getCollectionCategoryId())
        {
          $c->add(CollectionPeer::COLLECTION_CATEGORY_ID, $collection->getCollectionCategoryId());
        }
        $c->add(CollectionPeer::NUM_ITEMS, 4, Criteria::GREATER_EQUAL);
        $c->addAscendingOrderByColumn(CollectionPeer::SCORE);
        $c->addDescendingOrderByColumn(CollectionPeer::CREATED_AT);
        $c->setLimit(9);

        $this->collections = CollectionPeer::doSelect($c);
      }

      return 'NoCollectibles';
    }

    return sfView::SUCCESS;
  }

  public function executeCollectible(sfWebRequest $request)
  {
    $this->forward404Unless($this->getRoute() instanceof sfPropelRoute);

    /** @var $collectible Collectible */
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

    if (in_array($collection->getId(), array($pawn_stars['collection'], $american_pickers['collection'])))
    {
      $this->redirect('@aetn_collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug(), 301);
    }

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collectible))
    {
      $collectible->setNumViews($collection->getNumViews() + 1);
      $collectible->save();
    }

    /**
     * Figure out the previous and the next item in the collection
     */
    $collectible_ids = $collection->getCollectibleIds();

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

    if ($collectible->isForSale())
    {
      /* @var $collectible_for_sale CollectibleForSale */
      $collectible_for_sale = $collectible->getCollectibleForSale();
      $this->isSold = $collectible_for_sale->getIsSold() || $collectible_for_sale->getActiveCollectibleOffersCount() == 0;

      $this->collectible_for_sale = $collectible_for_sale;
      $this->form = new CollectibleForSaleBuyForm($collectible_for_sale);
    }

    $this->collector = $collector;
    $this->collection = $collection;
    $this->collectible = $collectible;
    $this->additional_multimedia = $collectible->getMultimedia(0, 'image', false);

    return sfView::SUCCESS;
  }

  public function executeCreate()
  {
    return sfView::SUCCESS;
  }
}
