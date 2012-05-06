<?php

class collectorComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    if (!$this->collector = CollectorPeer::retrieveByPk($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    $this->profile = $this->collector->getProfile();

    return sfView::SUCCESS;
  }

  public function executeIndexCollectiblesForSale()
  {
    $collector = $this->getVar('collector') ?: CollectorPeer::retrieveByPk($this->getRequestParameter('id'));

    if (!$collector) {
      return sfView::NONE;
    }

    $c = new Criteria();
    $c->setDistinct();
    $c->addJoin(CollectiblePeer::ID, CollectibleForSalePeer::COLLECTIBLE_ID, Criteria::RIGHT_JOIN);
    $c->add(CollectiblePeer::COLLECTOR_ID, $collector->getId());
    $c->addDescendingOrderByColumn(CollectibleForSalePeer::UPDATED_AT);

    $pager = new sfPropelPager('CollectibleForSale', 4);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->setCriteria($c);
    $pager->init();

    $this->pager = $pager;
    $this->collector = $collector;

    return $pager->getNbResults() == 0 ? sfView::NONE : sfView::SUCCESS;
  }

  public function executeIndexCollections()
  {
    $collector = $this->getVar('collector') ?: CollectorPeer::retrieveByPk($this->getRequestParameter('id'));

    if (!$collector) {
      return sfView::NONE;
    }

    $c = new Criteria();
    $c->add(CollectorCollectionPeer::COLLECTOR_ID, $collector->getId());
    $c->addDescendingOrderByColumn(CollectorCollectionPeer::UPDATED_AT);

    $pager = new sfPropelPager('CollectorCollection', 6);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->setCriteria($c);
    $pager->init();

    $this->pager = $pager;
    $this->collector = $collector;

    return $pager->getNbResults() == 0 ? sfView::NONE : sfView::SUCCESS;
  }
}
