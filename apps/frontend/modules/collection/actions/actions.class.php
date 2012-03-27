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

    $per_page = sfConfig::get('app_pager_list_collectibles_max', 16);

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

    $this->collectible = $collectible;
    $this->collector = $collector;
    $this->collectible = $collectible;
    $this->additional_multimedia = $collectible->getMultimedia(false);

    return sfView::SUCCESS;
  }

}
