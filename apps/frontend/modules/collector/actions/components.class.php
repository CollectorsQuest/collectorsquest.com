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

    $q = CollectibleForSaleQuery::create()
      ->joinCollectible()
      ->filterByCollector($collector)
      ->isForSale()
      ->orderByUpdatedAt(Criteria::DESC);

    $pager = new PropelModelPager($q, 4);
    $pager->setPage($this->getRequestParameter('p', 1));
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
    $c->addDescendingOrderByColumn(CollectorCollectionPeer::CREATED_AT);

    $pager = new sfPropelPager('CollectorCollection', 6);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->setCriteria($c);
    $pager->init();

    $this->pager = $pager;
    $this->collector = $collector;

    return $pager->getNbResults() == 0 ? sfView::NONE : sfView::SUCCESS;
  }
}
