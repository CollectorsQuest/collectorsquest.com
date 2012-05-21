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
    $this->total = $this->collector->countCollectorCollections();

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

  public function executeCollection(sfWebRequest $request)
  {
    /** @var $collection CollectorCollection */
    $collection = $this->getRoute()->getObject();
    $this->forward404Unless($this->getCollector()->isOwnerOf($collection));

    if ($request->getParameter('cmd'))
    {
      switch ($request->getParameter('cmd'))
      {
        case 'delete':
          $collection_name = $collection->getName();
          $collection->delete();

          $this->getUser()->setFlash(
            'success', sprintf('Your collection "%s" was deleted!', $collection_name)
          );

          $this->redirect('@mycq_collections');
          break;
      }
    }

    $form = new CollectorCollectionEditForm($collection);

    if ($request->isMethod('post'))
    {
      $taintedValues = $request->getParameter($form->getName());
      $form->bind($taintedValues, $request->getFiles($form->getName()));

      if ($form->isValid())
      {
        $collection->setCollectionCategoryId($form->getValue('collection_category_id'));
        $collection->setName($form->getValue('name'));
        $collection->setDescription($form->getValue('description'), 'html');
        $collection->setTags($form->getValue('tags'));

        try
        {
          $collection->save();

          $this->getUser()->setFlash("success", 'Changes were saved!');
          $this->redirect($this->getController()->genUrl(array(
            'sf_route'   => 'mycq_collection_by_slug',
            'sf_subject' => $collection,
          )));
        }
        catch (PropelException $e)
        {
          $this->getUser()->setFlash(
            'error', 'There was a problem while saving the information you provided!'
          );
        }
      }
      else
      {
        $this->defaults = $taintedValues;
        $this->getUser()->setFlash(
          'error', 'There were some problems, please take a look below.'
        );
      }
    }

    $this->total = $collection->countCollectionCollectibles();
    $this->collection = $collection;
    $this->form = $form;

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

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeCollectible(sfWebRequest $request)
  {
    /** @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();
    $this->forward404Unless($this->getCollector()->isOwnerOf($collectible));

    /** @var $collection CollectorCollection */
    $collection = $collectible->getCollectorCollection();

    if ($request->getParameter('cmd'))
    {
      switch ($request->getParameter('cmd'))
      {
        case 'delete':

          $name = $collectible->getName();

          // Delete the Collectible
          $collectible->delete();
          $this->getUser()->setFlash(
            'success', sprintf('Collectible "%s" was deleted!', $name)
          );

          $url = $this->generateUrl('mycq_collection_by_slug', array('sf_subject' => $collection));
          $this->redirect($url);

          break;
      }
    }

    $form = new CollectibleEditForm($collectible);

    if ($request->isMethod('post'))
    {
      $taintedValues = $request->getParameter('collectible');
      $form->bind($taintedValues, $request->getFiles('collectible'));

      if ($form->isValid())
      {
        $form->save();

        if ($this->bIsSeller)
        {
          if (isset($omItemForSaleForm) && $omItemForSaleForm->save())
          {
            if ($omItemForSaleForm->getValue('is_ready'))
            {
              $message = $this->__(
                'Your collectible has been posted to the Marketplace.
                 Click <a href="%url%">here</a> to view your collectibles for sale!',
                array('%url%' => $this->generateUrl('manage_marketplace'))
              );
            }
            else
            {
              $message = $this->__('Changes were saved!');
            }

            $this->getUser()->setFlash('success', $message);
          }
        }
        else
        {
          $this->getUser()->setFlash('success', $this->__('Changes were saved!'), true);
        }

        // if we save the form the request has to be redirected
        $this->redirect('mycq_collectible_by_slug', $form->getObject());
      }
      else
      {
        $this->defaults = $taintedValues;
        $this->getUser()->setFlash(
          'error', 'There was a problem while saving the information you provided!'
        );
      }
    }

    $this->collection = $collection;
    $this->collectible = $collectible;

    $this->form = $form;

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

    $this->redirect($request->getReferer() ? $request->getReferer() : '@mycq_collections');
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
