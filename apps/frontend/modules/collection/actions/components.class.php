<?php

class collectionComponents extends cqFrontendComponents
{

  public function executeSidebar()
  {
    if (!$this->collection = CollectorCollectionPeer::retrieveByPk($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    return sfView::SUCCESS;
  }

  public function executeSidebarCollectible()
  {
    $parameters = array('id' => $this->getRequestParameter('id'));
    if (!$this->collectible = CollectionCollectiblePeer::getObjectForRoute($parameters))
    {
      return sfView::NONE;
    }

    if ($this->collectible->isForSale())
    {
      /* @var $collectible_for_sale CollectibleForSale */
      $collectible_for_sale = $this->collectible->getCollectibleForSale();

      $this->isSold = $collectible_for_sale->getIsSold() || $collectible_for_sale->getActiveCollectibleOffersCount() == 0;
      $this->collectible_for_sale = $collectible_for_sale;
      $this->form = new CollectibleForSaleBuyForm($collectible_for_sale);
    }

    return sfView::SUCCESS;
  }

  public function executeCollectiblesReorder()
  {
    $this->_get_collection();

    if ($this->getUser()->isOwnerOf($this->collection))
    {
      $c = new Criteria();
      $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
      $c->addDescendingOrderByColumn(CollectionCollectiblePeer::CREATED_AT);

      $this->collectibles = $this->collection->getCollectibles($c);
    }

    return sfView::SUCCESS;
  }

  private function _get_collection()
  {
    if ($id = $this->getRequestParameter('id'))
    {
      $this->collection = CollectorCollectionPeer::retrieveByPk($id);
    }
    else if ($id = $this->getRequestParameter('collector_id'))
    {
      if ($collector = CollectorPeer::retrieveByPK($id))
      {
        $this->collection = $collector->getCollectionDropbox();
      }
    }
    else if ('0' === $id = $this->getRequestParameter('id'))
    {
      if ($collector = $this->getCollector())
      {
        $this->collection = $collector->getCollectionDropbox();
      }
    }
  }
}
