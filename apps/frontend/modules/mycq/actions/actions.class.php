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
    $this->redirectUnless($collector instanceof Collector, '@mycq_collections');

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
    $this->redirectUnless(
      $this->getCollector()->isOwnerOf($collection),
      '@mycq_collections'
    );

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
          'error', 'Please complete the fields in red below'
        );
      }
    }

    $collector = $this->getCollector();
    $dropbox = $collector->getCollectionDropbox();
    $this->dropbox_total = $dropbox->countCollectibles();

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
    $this->redirectUnless(
      $this->getCollector()->isOwnerOf($collection),
      '@mycq_collections'
    );

    $collectible = CollectibleQuery::create()
      ->findOneById($request->getParameter('collectible_id'));
    $this->redirectUnless(
      $this->getCollector()->isOwnerOf($collectible),
      '@mycq_collections'
    );

    $q = CollectionCollectibleQuery::create()
      ->filterByCollection($collection)
      ->filterByCollectible($collectible);

    $collection_collectible = $q->findOneOrCreate();
    $collection_collectible->save();

    $this->redirect('mycq_collectible_by_slug', $collection_collectible);
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeCollectible(sfWebRequest $request)
  {
    /** @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();
    $this->redirectUnless(
      $this->getCollector()->isOwnerOf($collectible),
      '@mycq_collections'
    );

    /** @var $collection CollectorCollection */
    $collection = $collectible->getCollectorCollection();

    /** @var $collector Collector */
    $collector = $this->getCollector();

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
        $this->getUser()->setFlash('error', 'Please complete the fields in red below');
      }
    }

    $c = new Criteria();
    $c->add(CollectionCollectiblePeer::COLLECTIBLE_ID, $collectible->getId(), Criteria::NOT_EQUAL);
    $c->setLimit(11);
    $this->collectibles = $collection->getCollectionCollectibles($c);

    $this->multimedia = $collectible->getMultimedia(0, 'image', false);

    $this->collection = $collection;
    $this->collectible = $collectible;

    $this->form = $form;
    $this->form_for_sale = $form['for_sale'];

    return sfView::SUCCESS;
  }

  public function executeMarketplace()
  {
    return sfView::SUCCESS;
  }

  public function executeUploadCancel(sfWebRequest $request)
  {
    $this->redirectUnless(
      $batch = $request->getParameter('batch'),
      '@mycq_collections'
    );

    CollectibleQuery::create()
      ->filterByCollector($this->getCollector())
      ->filterByBatchHash($batch)
      ->delete();

    $this->getUser()->setFlash(
      'error', 'The upload was cancelled and none of the items were uploaded'
    );

    $this->redirect('@mycq_collections');
  }

  public function executeUploadFinish(sfWebRequest $request)
  {
    $this->redirectUnless(
      $batch = $request->getParameter('batch'),
      '@mycq_collections'
    );

    $q = CollectibleQuery::create()
      ->filterByCollector($this->getCollector())
      ->filterByBatchHash($batch);

    $total = $q->count();

    if ($total > 0)
    {
      $this->getUser()->setFlash(
        'success', 'Total of <b>' . $total . '</b> items were uploaded successfully'
      );
    }
    else
    {
      $this->getUser()->setFlash(
        'error', 'There was a problem uploading your items and none were uploaded'
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

  public function executeWanted()
  {
    return sfView::SUCCESS;
  }

}
