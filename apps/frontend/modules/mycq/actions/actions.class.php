<?php

class mycqActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $this->redirect('@mycq_profile');
  }

  public function executeProfile()
  {
    return sfView::SUCCESS;
  }

  public function executeCollections()
  {
    $this->collector = $this->getUser()->getCollector();
    $this->collections_count = $this->collector->countCollectorCollections();

    return sfView::SUCCESS;
  }

  public function executeDropbox()
  {
    return sfView::SUCCESS;
  }

  public function executeCollection()
  {
    $collection = $this->getRoute()->getObject();
    $this->forward404Unless($this->getCollector()->isOwnerOf($collection));

    return sfView::SUCCESS;
  }

  /**
   * @param sfWebRequest $request
   */
  public function executeCollectionCollectibleCreate(sfWebRequest $request)
  {
    $collection = CollectorCollectionQuery::create()
      ->findOneById($request->getParameter('collection_id'));
    $this->forward404Unless($this->getCollector()->isOwnerOf($collection));

    $collectible = CollectibleQuery::create()
      ->findOneById($request->getParameter('collectible_id'));
    $this->forward404Unless($this->getCollector()->isOwnerOf($collectible));

    $q = CollectionCollectibleQuery::create()
      ->filterByCollection($collection)
      ->filterByCollectible($collectible);

    $collection_collectible = $q->findOneOrCreate();
    $collection_collectible->save();

    $this->redirect('mycq_collectible_by_slug', $collection_collectible);
  }

  public function executeCollectibles()
  {
    return sfView::SUCCESS;
  }

  public function executeCollectible()
  {
    return sfView::SUCCESS;
  }

  public function executeMarketplace()
  {
    return sfView::SUCCESS;
  }

  public function executeWanted()
  {
    return sfView::SUCCESS;
  }

  public function executeShoppingOrders()
  {
    return sfView::SUCCESS;
  }

  public function executeShoppingOrder()
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    /** @var $shopping_payment ShoppingPayment */
    $shopping_payment = $shopping_order->getShoppingPaymentRelatedByShoppingPaymentId();

    // Prepare request arrays
    $GetShippingAddressFields = array(
      'Key' => $shopping_payment->getProperty('paypal.pay_key')
    );
    $PayPalRequestData = array('GetShippingAddressFields' => $GetShippingAddressFields);

    $AdaptivePayments = cqStatic::getPayPaylAdaptivePaymentsClient();
    $result = $AdaptivePayments->GetShippingAddress($PayPalRequestData);

    dd($result);

    return sfView::SUCCESS;
  }
}
