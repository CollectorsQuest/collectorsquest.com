<?php

class aentComponents extends sfComponents
{

  public function executeSidebarAmericanPickers()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarPawnStars()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarCollectible()
  {
    if (!$this->collectible = CollectiblePeer::retrieveByPk($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    $american_pickers = sfConfig::get('app_aetn_american_pickers');
    $pawn_stars = sfConfig::get('app_aetn_pawn_stars');
    $storage_wars = sfConfig::get('app_aetn_storage_wars');

    if ($this->collectible->getCollectionId() === $american_pickers['collection']) {
      $this->brand = 'American Pickers';
    } else if ($this->collectible->getCollectionId() === $pawn_stars['collection']) {
      $this->brand = 'Pawn Stars';
    } else if ($this->collectible->getCollectionId() === $storage_wars['collection']) {
      $this->brand = 'Storage Wars';
    }

    return sfView::SUCCESS;
  }

}
