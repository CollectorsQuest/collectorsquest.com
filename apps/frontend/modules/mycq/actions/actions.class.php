<?php

class mycqActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $this->redirectIf(IceGateKeeper::locked('mycq_homepage'), '@mycq_profile');

    return sfView::SUCCESS;
  }

  public function executeProfile(sfWebRequest $request)
  {
    SmartMenu::setSelected('mycq_menu', 'profile');

    $collector_form = new CollectorEditForm($this->getCollector());
    $avatar_form = new CollectorAvatarForm($this->getCollector());

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
          $this->getUser()->getCollector()->getProfile()->updateProfileProgress();
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
    }

    $this->avatars = CollectorPeer::$avatars;
    $this->avatar_form = $avatar_form;

    $this->collector = $this->getUser()->getCollector();
    $this->collector_form = $collector_form;

    return sfView::SUCCESS;
  }

  public function executeProfileAccountInfo(sfWebRequest $request)
  {
    $collector_form = new CollectorEditForm($this->getCollector());
    $collector_form->useFields(array(
        'old_password', 'password', 'password_again'
    ));
    $email_form = new CollectorEmailChangeForm($this->getCollector());

    if (sfRequest::POST == $request->getMethod())
    {
      if ($request->hasParameter($collector_form->getName()))
      {
        $success = $collector_form->bindAndSave(
          $request->getParameter($collector_form->getName()),
          $request->getFiles($collector_form->getName())
        );

        if ($success)
        {
          $this->getUser()->setFlash('success',
            'You have successfully updated your profile.');

          return $this->redirect('mycq_profile_account_info');
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

          return $this->redirect('mycq_profile_account_info');
        }
        else
        {
          $this->getUser()->setFlash('error',
            'There was an error while changing your e-mail address.
             Please see below.');
        }
      }
    }

    $this->collector = $this->getCollector();
    $this->email_form = $email_form;
    $this->collector_form = $collector_form;

    return sfView::SUCCESS;
  }

  public function executeProfileAddresses(sfWebRequest $request)
  {
    $this->collector_addresses = $this->getCollector()->getCollectorAddresses();

    return sfView::SUCCESS;
  }

  public function executeProfileAddressesNew(sfWebRequest $request)
  {
    $address = new CollectorAddress();
    $address->setCollector($this->getCollector());
    $form = new FrontendCollectorAddressForm($address);

    if (sfRequest::POST == $request->getMethod())
    {
      if ($form->bindAndSave($request->getParameter($form->getName())))
      {
        $this->redirect('@mycq_profile_addresses');
      }
    }
    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeProfileAddressesEdit(sfWebRequest $request)
  {
    $address = $this->getRoute()->getObject();
    $this->forward404Unless($this->getCollector()->isOwnerOf($address));

    $form = new FrontendCollectorAddressForm($address);

    if (sfRequest::POST == $request->getMethod())
    {
      if ($form->bindAndSave($request->getParameter($form->getName())))
      {
        $this->redirect('@mycq_profile_addresses');
      }
    }

    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeProfileAddressesDelete(sfWebRequest $request)
  {
    $address = $this->getRoute()->getObject();
    $this->forward404Unless($this->getUser()->isOwnerOf($address));

    if (sfRequest::DELETE == $request->getMethod())
    {
      $address->delete();
      $this->getUser()->setFlash('success',
        $this->__('You have successfully removed an address from your account.'));

      return $this->redirect('@mycq_profile_addresses');
    }

    $this->collector_address = $address;

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

        $this->getUser()->setFlash('success', 'All uploaded photos were deleted!', true);
        break;
    }

    return $this->redirect('@mycq_collections');
  }


  public function executeCollections()
  {
    SmartMenu::setSelected('mycq_menu', 'collections');

    $this->collector = $this->getUser()->getCollector();
    $this->total = $this->collector->countCollectorCollections();

    return sfView::SUCCESS;
  }

  public function executeCollection(sfWebRequest $request)
  {
    SmartMenu::setSelected('mycq_menu', 'collections');

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
    $form_shipping = new ShippingRatesCollectionForm($collectible, array(
        'tainted_request_values' =>
            $request->getParameter('shipping_rates_collection'),
    ));

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

      if ($form['for_sale'] && IceGateKeeper::open('collectible_shipping'))
      {
        $form_shipping->bind($request->getParameter('shipping_rates_collection'));
      }

      if (
        $form->isValid() &&
        (!$form_shipping->isBound() || $form_shipping->isValid())
      )
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
    $this->form_shipping = $form_shipping;

    if ($collectible->isForSale()) {
      SmartMenu::setSelected('mycq_menu', 'marketplace');
    } else  {
      SmartMenu::setSelected('mycq_menu', 'collections');
    }

    return sfView::SUCCESS;
  }

  public function executeMarketplace()
  {
    $this->redirectUnless(IceGateKeeper::open('mycq_marketplace'), '@mycq');

    SmartMenu::setSelected('mycq_menu', 'marketplace');

    // Get the Seller
    $seller = $this->getSeller(true);

    // We cannot do anything more here if not a Seller
    $this->redirectUnless($seller instanceof Seller, 'seller/packages');

    $q = CollectibleForSaleQuery::create()
      ->filterByCollector($seller)
      ->isForSale();
    $this->total = $q->count();

    $q = CollectibleForSaleQuery::create()
      ->filterByCollector($seller)
      ->filterByIsSold(true);
    $this->sold_total = $q->count();

    if ($dropbox = $seller->getCollectionDropbox())
    {
      $this->dropbox_total = $dropbox->countCollectibles();
    }
    else
    {
      $this->dropbox_total = 0;
    }

    // Make the seller available to the template
    $this->seller = $seller;

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
      'error', 'The upload was cancelled and none of the photos were uploaded'
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
        'success', 'Total of <b>' . $total . '</b> photos were uploaded successfully'
      );
    }
    else
    {
      $this->getUser()->setFlash(
        'error', 'There was a problem uploading your photos and none were uploaded'
      );
    }

    $redirect = $request->getReferer() ?
      $request->getReferer().'#dropbox' :
      '@mycq_collections#dropbox';

    $this->redirect($redirect);
  }

  public function executeShoppingOrders()
  {
    SmartMenu::setSelected('mycq_menu', 'marketplace');

    return sfView::SUCCESS;
  }

  public function executeShoppingOrder()
  {
    SmartMenu::setSelected('mycq_menu', 'marketplace');

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
    SmartMenu::setSelected('mycq_menu', 'wanted');

    return sfView::SUCCESS;
  }

}
