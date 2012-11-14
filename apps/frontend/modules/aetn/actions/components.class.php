<?php

class aetnComponents extends cqFrontendComponents
{

  public function executeSidebarAmericanPickers()
  {
    $this->collection = $this->getVar('collection');

    return sfView::SUCCESS;
  }

  public function executeSidebarAmericanRestoration()
  {
    $this->collection = $this->getVar('collection');

    return sfView::SUCCESS;
  }

  public function executeSidebarPawnStars()
  {
    $this->collection = $this->getVar('collection');

    return sfView::SUCCESS;
  }

  public function executeSidebarPickedOff()
  {
    $this->collection = $this->getVar('collection');

    return sfView::SUCCESS;
  }

  public function executeSidebarStorageWars()
  {
    return sfView::SUCCESS;
  }

  public function executeHeaderPetroliana()
  {
    return sfView::SUCCESS;
  }

  public function executeHeaderRailroadiana()
  {
    return sfView::SUCCESS;
  }

  public function executeHeaderRooseveltiana()
  {
    return sfView::SUCCESS;
  }

  public function executeFranksPicksCollectiblesForSale()
  {
    /* @var $franks_picks array */
    $aetn_shows = sfConfig::get('app_aetn_shows', array());

    $collection = CollectorCollectionQuery::create()->findOneById($aetn_shows['american_pickers']['franks_picks']);

    /*
     * Collectibles are not public right now, when the become public we should use FrontendQuery
     * $q = FrontendCollectionCollectibleQuery::create()
     */
    $q = CollectionCollectibleQuery::create()
      ->filterByCollection($collection)
      //->isForSale()
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $p = $this->getRequestParameter('p', 1);

    $pager = new PropelModelPager($q, 12);
    $pager->setPage($p);
    $pager->init();
    $this->pager = $pager;

    $this->collection = $collection;

    // if we are trying to get an out of bounds page
    if ($p > 1 && $p > $pager->getLastPage())
    {
      // return empty response
      return sfView::NONE;
    }
  }
}
