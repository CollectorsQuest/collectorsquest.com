<?php

class collectionComponents extends cqFrontendComponents
{
  public function executeSidebar()
  {
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

    $this->offerPrice = $collectible_for_sale->getPrice();
    if ($collectible_for_sale)
    {
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
