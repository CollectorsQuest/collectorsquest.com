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

  public function executeSoldRelatedItems()
  {
    // Either get the Collector from the parameter holder or try to find it by ID
    $collector = $this->getVar('collector') ?: CollectorPeer::retrieveByPk($this->getRequestParameter('id'));

    // We cannot continue without a valid Collector
    if (!$collector)
    {
      return sfView::NONE;
    }

    // Set the limit of Collectibles For Sale to show
    $limit = (int) $this->getVar('limit') ?: 4;

    /* @var $q FrontendCollectibleForSaleQuery */
    $q = FrontendCollectibleForSaleQuery::create()
      ->filterByCollector($collector)
      ->isForSale()
      ->orderByUpdatedAt(Criteria::DESC)
      ->limit($limit);

    if (($collectible = $this->getVar('collectible')) && $collectible instanceof Collectible)
    {
      $q->filterByCollectibleId($collectible->getId(), Criteria::NOT_EQUAL);
    }

    $number_of_items = $q->count();
    /*
     * we want to always display 4 (or $limit) items
     * if Collector does not have enough Items we add random
     */
    if ($number_of_items > 0)
    {
      $this->collectibles_for_sale = $q->find();
      $this->title = $this->getVar('title') ?: "Item is Sold! See more from " . $collector->getDisplayName();
      // we want to display link to seller store
      $this->display_store_link = true;
    }
    else
    {
      $this->collectibles_for_sale = array();
      $this->title = $this->getVar('title') ?: "Item is Sold! See more unique Items for Sale!";
      // we don't want to display link to seller store as it should be empty
      $this->display_store_link = false;
    }

    if ($number_of_items < $limit)
    {
      /* @var $random_query FrontendCollectibleForSaleQuery */
      $random_query = FrontendCollectibleForSaleQuery::create()
        ->addAscendingOrderByColumn('RAND()')
        ->isForSale()
        ->limit($limit - $number_of_items);

      $random_collectibles_for_sale = $random_query->find();

      // do we have any Items for Sale from this Collector?
      if (!empty($this->collectibles_for_sale))
      {
        // add more items for sale to those of the Collector
        $this->collectibles_for_sale->exchangeArray(
          array_merge($this->collectibles_for_sale->getArrayCopy(), $random_collectibles_for_sale->getArrayCopy())
        );
      }
      else
      {
        // display only random Items
        $this->collectibles_for_sale = $random_collectibles_for_sale;
      }
    }

    //@todo figure out a way to not repeat items here and in widgetCollectiblesForSale

    $this->collector = $collector;

    return sfView::SUCCESS;
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
