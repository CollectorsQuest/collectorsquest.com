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
