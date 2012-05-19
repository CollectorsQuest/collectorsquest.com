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

  public function executeDropbox(sfWebRequest $request)
  {
    $collector = $this->getCollector();
    $this->forward404Unless($collector instanceof Collector);

    switch ($request->getParameter('cmd'))
    {
      case 'empty':
        $c = new Criteria();
        $c->add(CollectiblePeer::COLLECTOR_ID, $collector->getId());

        $c->addJoin(
          CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN
        );
        $c->add(CollectionCollectiblePeer::COLLECTION_ID, null, Criteria::ISNULL);

        /** @var $collectibles Collectible[] */
        if ($collectibles = CollectiblePeer::doSelect($c))
        {
          foreach ($collectibles as $collectible)
          {
            $collectible->delete();
          }
        }

        $this->getUser()->setFlash('success', 'Your dropbox was emptied!', true);
        break;
    }

    $this->redirect('@mycq_collections');
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

  public function executeUploadCancel(sfWebRequest $request)
  {
    $batch = $request->getParameter('batch');
    $this->forward404Unless($batch);

    CollectibleQuery::create()
      ->filterByCollector($this->getCollector())
      ->filterByBatchHash($batch)
      ->delete();

    $this->getUser()->setFlash(
      'error', 'The upload was cancelled and none of the photos were uploaded'
    );

    $this->redirect('@mycq_collections');
  }

  public function executeUploadFinish(sfWebRequest $request)
  {
    $batch = $request->getParameter('batch');
    $this->forward404Unless($batch);

    $q = CollectibleQuery::create()
      ->filterByCollector($this->getCollector())
      ->filterByBatchHash($batch);

    $total = $q->count();

    if ($total > 0)
    {
      $this->getUser()->setFlash(
        'success', 'Total of <b>' . $total . '</b> photos were uploaded successfully'
      );
    }
    else
    {
      $this->getUser()->setFlash(
        'error', 'There was a problem uploading your photos and none were uploaded'
      );
    }

    $this->redirect('@mycq_collections');
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
