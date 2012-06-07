<?php

class mycqActions extends cqFrontendActions
{
  public function executeIndex()
  {
    return $this->redirect('@mycq_profile');
  }

  public function executeProfile(sfWebRequest $request)
  {
    $this->collector = $this->getCollector();

    $collector_form = new CollectorEditForm($this->collector);
    $avatar_form = new CollectorAvatarForm($this->collector);
    $email_form = new CollectorEmailChangeForm($this->collector);

    if (sfRequest::POST == $request->getMethod())
    {
      if ($request->hasParameter($avatar_form->getName()))
      {
        $success = $avatar_form->bindAndSave(
          $request->getParameter($avatar_form->getName()),
          $request->getFiles($avatar_form->getName())
        );

        if ($success)
        {
          $this->getUser()->setFlash('success',
            'You have successfully updated your profile photo.');

          return $this->redirect('mycq_profile');
        }
        else
        {
          $this->getUser()->setFlash('error',
            'There was an error when saving your profile photo.');
        }
      }
      else if ($request->hasParameter($collector_form->getName()))
      {
        $success = $collector_form->bindAndSave(
          $request->getParameter($collector_form->getName()),
          $request->getFiles($collector_form->getName())
        );

        if ($success)
        {
          $this->getUser()->setFlash('success',
            'You have successfully updated your profile.');

          return $this->redirect('mycq_profile');
        }
        else
        {
          $this->getUser()->setFlash('error',
            'There was an error while updating your profile.
             Please see below.');
        }
      }
      else if ($request->hasParameter($email_form->getName()))
      {
        $collector_email = $email_form->bindAndCreateCollectorEmail(
          $request->getParameter($email_form->getName()));

        if ($collector_email)
        {
          $cqEmail = new cqEmail($this->getMailer());
          $cqEmail->send('Collector/verify_new_email', array(
              'to' => $email,
              'params' => array(
                  'collector' => $this->collector,
                  'collector_email' => $collector_email,
              )
          ));

          $this->getUser()->setFlash('success',
            'A verification email was sent to '.$this->collector->getEmail());

          return $this->redirect('mycq_profile');
        }
        else
        {
          $this->getUser()->setFlash('error',
            'There was an error while changing your e-mail address.
             Please see below.');
        }
      }

    }

    $this->avatars = CollectorPeer::$avatars;
    $this->avatar_form = $avatar_form;

    $this->collector = $this->getUser()->getCollector();
    $this->collector_form = $collector_form;

    $this->email_form = $email_form;

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

        $this->getUser()->setFlash('success', 'All Items to Sort were deleted!', true);
        break;
    }

    return $this->redirect('@mycq_collections');
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

          return $this->redirect('@mycq_collections');
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
        $values = $form->getValues();

        $collection->setCollectionCategoryId($values['collection_category_id']);
        $collection->setName($values['name']);
        $collection->setDescription($values['description'], 'html');
        $collection->setTags($values['tags']);

        if ($values['thumbnail'] instanceof sfValidatedFile)
        {
          $collection->setThumbnail($values['thumbnail']);
        }

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

    return $this->redirect('mycq_collectible_by_slug', $collection_collectible);
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
      if (isset($taintedValues['collection_collectible_list']))
      {
        $taintedValues['collection_collectible_list'] = array_filter(
          $taintedValues['collection_collectible_list']
        );
        $taintedValues['collection_collectible_list'] = array_values(
          $taintedValues['collection_collectible_list']
        );
      }

      $form->bind($taintedValues, $request->getFiles('collectible'));

      if ($form->isValid())
      {
        $for_sale = $form->getValue('for_sale');

        if (
          null !== $for_sale &&
          $for_sale['is_ready'] !== $collectible->getCollectibleForSale()->getIsReady() &&
          $for_sale['is_ready'] === true
        )
        {
          $message = $this->__(
            'Your collectible has been posted to the Market.
             Click <a href="%url%">here</a> to manage your collectibles for sale!',
            array('%url%' => $this->generateUrl('mycq_marketplace'))
          );
        }
        else
        {
          $message = $this->__('Changes were saved!');
        }

        try
        {
          $form->save();
          $this->getUser()->setFlash('success', $message, true);

          // If we save the form the request has to be redirected
          $this->redirect('mycq_collectible_by_slug', $form->getObject());
        }
        catch (PropelException $e)
        {
          $this->getUser()->setFlash(
            'error', 'There was a problem saving your information'
          );
        }
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
    $this->form_for_sale = isset($form['for_sale']) ? $form['for_sale'] : null;

    return sfView::SUCCESS;
  }

  public function executeMarketplace()
  {
    $collector = $this->getCollector();

    $q = CollectibleForSaleQuery::create()
      ->filterByCollector($collector)
      ->isForSale();

    $this->total = $q->count();

    $dropbox = $collector->getCollectionDropbox();
    $this->dropbox_total = $dropbox->countCollectibles();

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

    return $this->redirect('@mycq_collections');
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

    return $this->redirect($request->getReferer() ? $request->getReferer() : '@mycq_collections');
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
