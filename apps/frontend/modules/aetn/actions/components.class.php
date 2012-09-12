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
    // use same Categories for "From the Market" as American Pickers for now
    $american_pickers = sfConfig::get('app_aetn_american_pickers');
    $this->collection = CollectorCollectionQuery::create()->findOneById($american_pickers['collection']);

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

}
