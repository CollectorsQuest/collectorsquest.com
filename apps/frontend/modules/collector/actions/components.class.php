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

    // if we are not viewing our own profile
    if ($this->getCollector() && ($this->getCollector()->getId() != $this->collector->getId()))
    {
      // setup PM form
      $this->pm_form = new ComposeAbridgedPrivateMessageForm(
        $this->getCollector(), $this->collector,
        'A message from '.$this->getCollector()->getDisplayName()
      );
    }

    $this->about_me = $this->profile->getProperty('about.me');
    $this->about_collections = $this->profile->getProperty('about.collections');
    $this->about_interests = $this->profile->getProperty('about.interests');

    $this->store_welcome = $this->collector->getSellerSettingsWelcome();
    $this->store_shipping = $this->collector->getSellerSettingsShipping();
    $this->store_refunds = $this->collector->getSellerSettingsRefunds();
    $this->store_return_policy = $this->collector->getSellerSettingsReturnPolicy();
    $this->store_additional_policies = $this->collector->getSellerSettingsAdditionalPolicies();

    return sfView::SUCCESS;
  }

  public function executeIndexCollectiblesForSale()
  {
    $collector = $this->getVar('collector') ?: CollectorPeer::retrieveByPk($this->getRequestParameter('id'));

    if (!$collector)
    {
      return sfView::NONE;
    }

    $q = CollectibleForSaleQuery::create()
      ->joinCollectible()
        ->useCollectibleQuery()
        ->filterByIsPublic(true)
        ->endUse()
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

    if (!$collector)
    {
      return sfView::NONE;
    }

    /** @var $q CollectorCollectionQuery */
    $q = CollectorCollectionQuery::create()
      ->filterByIsPublic(true)
      ->filterByCollector($collector)
      ->addJoin(CollectorCollectionPeer::ID, CollectionCollectiblePeer::COLLECTION_ID, Criteria::RIGHT_JOIN)
      ->orderByCreatedAt(Criteria::DESC)
      ->groupById();

    $pager = new PropelModelPager($q, 6);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();

    $this->pager = $pager;
    $this->collector = $collector;

    return $pager->getNbResults() == 0 ? sfView::NONE : sfView::SUCCESS;
  }
}
