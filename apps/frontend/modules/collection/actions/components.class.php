<?php

class collectionComponents extends cqFrontendComponents
{

  public function executeSidebar()
  {
    return ($this->collection = $this->_get_collection()) ? sfView::SUCCESS : sfView::NONE;
  }

  public function executeSidebarCollectible()
  {
    $this->aetn_show = $this->getVar('aetn_show');
    $this->collectible = $this->getVar('collectible');

    // is user coming from the marketplace page?
    $this->ref_marketplace = $this->getRequestParameter('ref');
    if ($this->ref_marketplace !== 'mp')
    {
      $this->ref_marketplace = false;
    }
    else
    {
      $this->ref_marketplace = true;
    }

    // We need a collectible for building the sidebar
    if (!$this->collectible)
    {
      return sfView::NONE;
    }

    /* @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = $this->getVar('collectible_for_sale');

    // Are we dealing with an item for sale?
    if ($collectible_for_sale && $collectible_for_sale->isForSale())
    {
      $this->isSold = $collectible_for_sale->getIsSold();
      $this->collectible_for_sale = $collectible_for_sale;
      $this->form = new CollectibleForSaleBuyForm($collectible_for_sale);
    }

    return sfView::SUCCESS;
  }

  public function executeCollectiblesReorder()
  {
    $this->collection = $this->_get_collection();

    if ($this->getUser()->isOwnerOf($this->collection))
    {
      $c = new Criteria();
      $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
      $c->addDescendingOrderByColumn(CollectionCollectiblePeer::CREATED_AT);

      $this->collectibles = $this->collection->getCollectibles($c);

      return sfView::SUCCESS;
    }

    return sfView::NONE;
  }

  public function executeSlot1SoldCollectibleRelated()
  {
    if (cqGateKeeper::locked('collectible_for_sale_related', 'feature'))
    {
      return sfView::NONE;
    }

    /* @var $collectible Collectible|CollectionCollectible */
    $collectible = CollectiblePeer::retrieveByPk($this->getRequestParameter('id'));

    // We cannot continue if no Collectible or the Collectible is not sold
    if (!$collectible || !$collectible->isSold())
    {
      return sfView::NONE;
    }

    $collector = $collectible->getCollector();
    $this->title = $this->getVar('title') ?: 'Here are some more items for sale from ' . $collector->getDisplayName();

    // Set the limit of Collectibles For Sale to show
    $limit = $this->getVar('limit', 6);

    /* @var $q FrontendCollectibleForSaleQuery */
    $q = FrontendCollectibleForSaleQuery::create()
      ->filterByCollector($collector)
      ->filterByCollectible($collectible, Criteria::NOT_EQUAL)
      ->isForSale()
      ->orderByUpdatedAt(Criteria::DESC)
      ->limit($limit);
    $this->collectibles_for_sale = $q->find();

    if (count($this->collectibles_for_sale) < $limit)
    {
      /* @var $query_related FrontendCollectibleForSaleQuery */
      $query_related = FrontendCollectibleForSaleQuery::create()
        ->filterByCollector($collector, Criteria::NOT_EQUAL)
        ->filterByCollectible($collectible, Criteria::NOT_EQUAL)
        ->filterByContentCategoryWithDescendants($collectible->getContentCategory())
        ->orderByUpdatedAt(Criteria::DESC)
        ->isForSale()
        ->limit($limit - count($this->collectibles_for_sale));

      $related_collectibles_for_sale = $query_related->find();

      // do we have any Items for Sale from this Collector?
      if (count($this->collectibles_for_sale) > 0)
      {
        // add more items for sale to those of the Collector
        $this->collectibles_for_sale->exchangeArray(
          array_merge($this->collectibles_for_sale->getArrayCopy(), $related_collectibles_for_sale->getArrayCopy())
        );
      }
      else
      {
        // display only related items for sale
        $this->collectibles_for_sale = $related_collectibles_for_sale;
        $this->title = $this->getVar('title') ?: 'Here are some related items for the Market!';
      }
    }

    $this->setComponentVar(
      'exclude_collectible_ids', $this->collectibles_for_sale->toKeyValue('CollectibleId', 'CollectibleId'),
      $action = 'widgetCollectiblesForSale', $module = '_sidebar'
    );

    $this->collector = $collector;

    return count($this->collectibles_for_sale) >= 4 ? sfView::SUCCESS : sfView::NONE;
  }

  private function _get_collection()
  {
    $collection = null;

    if ($id = $this->getRequestParameter('id'))
    {
      $collection = CollectorCollectionPeer::retrieveByPk($id);
    }
    else if ($id = $this->getRequestParameter('collector_id'))
    {
      if ($collector = CollectorPeer::retrieveByPK($id))
      {
        $collection = $collector->getCollectionDropbox();
      }
    }
    else if ('0' === $id = $this->getRequestParameter('id'))
    {
      if ($collector = $this->getCollector())
      {
        $collection = $collector->getCollectionDropbox();
      }
    }

    return $collection;
  }

}
