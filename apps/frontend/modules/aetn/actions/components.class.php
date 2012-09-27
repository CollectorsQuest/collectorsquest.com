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

  public function executeHeaderMenWhoBuiltAmerica()
  {
    return sfView::SUCCESS;
  }
}
