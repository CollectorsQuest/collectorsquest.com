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

    /*
     * Black History Collectible Ids
     * https://www.collectorsquest.com/blog/wp-admin/post.php?post=34898&action=edit
     */
    $this->black_history_collectible_ids = array(
      104044, 100808, 100794, 104048, 100811, 100851, 100817, 100820, 22214, 100825, 100854,
      101198, 101201, 81340, 101204, 101208, 101353, 101203, 101206, 101214, 101222, 101350,
      101338, 101257, 101265, 101251, 101332, 101349, 101357, 101291, 101211, 101248, 101272,
      101275, 101277, 101328, 101329, 101334, 101335, 101360, 78575, 78578, 100765, 100767,
      100772, 100787, 89589, 82378, 91679,81254,82374,17119,100783,100776, 100782, 22215,
      22212, 22209, 22208, 22203, 22207, 22206, 22202, 101343, 101302, 101341, 101340, 101301,
      101285, 101281, 101323, 101359, 101347, 100856, 100870, 100871, 100873, 100874, 100875,
      100876, 100877, 101194, 101195, 101167, 101166, 101169, 101171, 101174, 101175, 101177,
      101179, 101181, 101183, 101185, 101187, 32830, 101190, 101191, 101298, 101297, 101319,
      101321, 22443, 22440, 22439, 32827, 20874, 20870, 20869, 20885, 20887, 20886, 26207, 32595,
      22200, 22201, 22210, 22213
    );

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
