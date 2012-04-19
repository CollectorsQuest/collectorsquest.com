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
    if (!$this->collectible = CollectiblePeer::retrieveByPk($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    /* @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = $this->collectible->getCollectibleForSale();

    if ($collectible_for_sale)
    {
      $this->offerPrice = $collectible_for_sale->getPrice();

      $offer = $collectible_for_sale->getCollectibleOfferByBuyer($this->getUser()->getId(), 'counter');
      if ($offer)
      {
        $this->offerPrice = $offer->getPrice();
      }
      $this->isSold = $collectible_for_sale->getIsSold() || $collectible_for_sale->getActiveCollectibleOffersCount();

      $this->form = new CollectibleForSaleBuyForm($collectible_for_sale);
    }

    return sfView::SUCCESS;
  }

}
