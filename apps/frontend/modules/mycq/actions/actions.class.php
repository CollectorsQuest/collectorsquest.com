<?php

class mycqActions extends cqFrontendActions
{

  public function executeIndex()
  {
    // Redirect to Collections if the homepage is not allowed
    $this->redirectUnless(IceGateKeeper::open('mycq_homepage'), '@mycq_collections');

    // Set the "home" as the active mycq menu item
    SmartMenu::setSelected('mycq_menu', 'home');

    $this->collector = $this->getUser()->getCollector();

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
          $this->getUser()->setFlash(
            'success', 'You have successfully updated your profile photo.'
          );

          $this->redirect('mycq_profile');
        }
        else
        {
          $this->getUser()->setFlash(
            'error', 'There was an error when saving your profile photo.'
          );
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
          $this->getUser()->setFlash(
            'success', 'You have successfully updated your profile.'
          );

          $this->redirect('mycq_profile');
        }
        else
        {
          $this->getUser()->setFlash(
            'error', 'There was an error while updating your profile.
                      Please see below.'
          );
        }
      }
    }

    $this->avatars = CollectorPeer::$default_avatar_ids;
    $this->avatar_form = $avatar_form;

    $this->collector = $this->getUser()->getCollector();
    $this->collector_form = $collector_form;

    if ($this->image = $this->collector->getPhoto())
    {
      $this->aviary_hmac_message = $this->getUser()->hmacSignMessage(
        json_encode(array('multimedia-id' => $this->image->getId())),
        cqConfig::getCredentials('aviary', 'hmac_secret')
      );
    }

    return sfView::SUCCESS;
  }

  public function executeProfileAccountInfo(sfWebRequest $request)
  {
    SmartMenu::setSelected('mycq_menu', 'profile');

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

          $this->redirect('mycq_profile_account_info');
        }
        else
        {
          $this->getUser()->setFlash(
            'error', 'There was an error while updating your profile.
                      Please see below.'
          );
        }
      }
      else if ($request->hasParameter($email_form->getName()))
      {
        $collector_email = $email_form->bindAndCreateCollectorEmail(
          $request->getParameter($email_form->getName())
        );

        if ($collector_email)
        {
          $cqEmail = new cqEmail($this->getMailer());
          $cqEmail->send('Collector/verify_new_email', array(
            'to'     => $collector_email->getEmail(),
            'params' => array(
              'collector'       => $collector_email->getCollector(),
              'collector_email' => $collector_email,
            )
          ));

          $this->getUser()->setFlash('success',
            'A verification email was sent to ' . $collector_email->getEmail()
          );

          $this->redirect('mycq_profile_account_info');
        }
        else
        {
          $this->getUser()->setFlash(
            'error', 'There was an error while changing your e-mail address.
                      Please see below.'
          );
        }
      }
    }

    $this->collector = $this->getCollector();
    $this->email_form = $email_form;
    $this->collector_form = $collector_form;

    return sfView::SUCCESS;
  }

  /**
   * Allow the user to change email notification options, as well as newsletter
   * options
   */
  public function executeProfileEmailPreferences(cqWebRequest $request)
  {
    /* @var $collector Collector */
    $collector = $this->getCollector();

    $_preferences = array(
      'opt_out'          => CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER_OPT_OUT,
      'newsletter'       => CollectorPeer::PROPERTY_PREFERENCES_NEWSLETTER,
      'comments'         => CollectorPeer::PROPERTY_NOTIFICATIONS_COMMENT,
      'comments_opt_out' => CollectorPeer::PROPERTY_NOTIFICATIONS_COMMENT_OPT_OUT,
      'messages'         => CollectorPeer::PROPERTY_NOTIFICATIONS_MESSAGE,
      'messages_opt_out' => CollectorPeer::PROPERTY_NOTIFICATIONS_MESSAGE_OPT_OUT
    );

    // Assume there are no properties changed in this request
    $_property_changed = false;

    foreach ($_preferences as $key => $property)
    {
      if ($request->hasParameter($key))
      {
        $collector->setProperty($property, (boolean)$request->getParameter($key));
        $_property_changed = $key;
      }

      if (false !== $_property_changed)
      {
        $collector->save();

        $this->getUser()->setFlash('success', sprintf(
          'You\'ve successfully changed your %s notification settings.',
          $_property_changed
        ));
      }
    }

    if (false !== $_property_changed)
    {
      return $this->redirect('@mycq_profile_email_preferences');
    }

    $this->collector = $collector;

    return sfView::SUCCESS;
  }

  public function executeProfileAddresses()
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
        $this->getUser()->setFlash('success',
          'You have successfully added a new address.');

        $this->redirect('@mycq_profile_addresses');
      }
    }
    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeProfileAddressesEdit(sfWebRequest $request)
  {
    /** @var $address CollectorAddress */
    $address = $this->getRoute()->getObject();

    $this->forward404Unless($this->getCollector()->isOwnerOf($address));

    $form = new FrontendCollectorAddressForm($address);

    if (sfRequest::POST == $request->getMethod())
    {
      if ($form->bindAndSave($request->getParameter($form->getName())))
      {
        $this->getUser()->setFlash('success',
          'You have successfully edited your address.');

        $this->redirect('@mycq_profile_addresses');
      }
    }

    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeProfileAddressesDelete(sfWebRequest $request)
  {
    /** @var $address CollectorAddress */
    $address = $this->getRoute()->getObject();

    $this->forward404Unless($this->getUser()->isOwnerOf($address));

    if (sfRequest::DELETE == $request->getMethod())
    {
      $address->delete();
      $this->getUser()->setFlash('success',
        $this->__('You have successfully removed an address from your account.'));

      $this->redirect('@mycq_profile_addresses');
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

        break;
    }

    $this->redirect('@mycq_collections');
  }

  public function executeCollections()
  {
    SmartMenu::setSelected('mycq_menu', 'collections');

    $this->collector = $this->getUser()->getCollector();
    $this->total = $this->collector->countCollectorCollections();

    // determine weather to show message for incomplete collections/collectibles
    $this->incomplete_collections = false;

    /*
     * this variable will be set true only if user doesn't have
     * incomplete collections but has incomplete collectibles
     */
    $this->incomplete_collectibles = false;

    if (IceGateKeeper::open('mycq_incomplete', 'page'))
    {
      $q = CollectorCollectionQuery::create()
        ->filterByCollector($this->collector)
        ->isIncomplete();
      if ($q->count() > 0)
      {
        $this->incomplete_collections = true;
      }
      else
      {
        $q = CollectibleQuery::create()
          ->filterByCollector($this->collector)
          ->isPartOfCollection()
          ->isIncomplete();
        if ($q->count() > 0)
        {
          $this->incomplete_collections = true;
          $this->incomplete_collectibles = true;
        }
      }
    }

    return sfView::SUCCESS;
  }

  public function executeCollectionCollectibleCreate(sfWebRequest $request)
  {
    /** @var $collection CollectorCollection */
    $collection = CollectorCollectionQuery::create()
      ->findOneById($request->getParameter('collection_id'));

    $this->redirectUnless(
      $this->getCollector()->isOwnerOf($collection),
      '@mycq_collections'
    );

    /** @var $collectible Collectible */
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

    // auto-set collection thumbnail if none set yet
    if (1 == $collection->countCollectibles() && !$collection->hasThumbnail())
    {
      $collection->setPrimaryImage(
        $collectible->getPrimaryImage()->getAbsolutePath('original')
      );
      $collection->save();
    }

    if (1 == $collectible->countCollections(new Criteria()))
    {
      // Give the collectible the same category as the collection
      $collectible->setContentCategoryId($collection->getContentCategoryId());
      $collectible->save();
    }

    return $this->redirect($this->getController()->genUrl(array(
      'sf_route'     => 'mycq_collectible_by_slug',
      'sf_subject'   => $collection_collectible,
      'suggest_tags' => true,
    )));
  }

  public function executeCollectible(sfWebRequest $request)
  {
    /** @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();

    /** @var $collection CollectorCollection */
    $collection = $collectible->getCollectorCollection();

    /**
     * Handle sold/purchased Collectibles
     */
    if ($collectible->isWasForSale() && $collectible->getCollectibleForSale()->getIsSold())
    {
      SmartMenu::setSelected('mycq_menu', 'marketplace');

      $this->collectible = $collectible;
      $this->multimedia = $collectible->getMultimedia(0, 'image', false);

      $this->shopping_order = ShoppingOrderQuery::create()
        ->filterByCollectibleId($collectible->getId())
        ->joinShoppingPaymentRelatedByShoppingPaymentId()
        ->useShoppingPaymentRelatedByShoppingPaymentIdQuery()
        ->filterByStatus(ShoppingPaymentPeer::STATUS_COMPLETED)
        ->endUse()
        ->findOne();

      if ($this->shopping_order instanceof ShoppingOrder)
      {
        $this->shopping_payment = $this->shopping_order->getShoppingPayment();

        $this->buyer = $this->shopping_order->getBuyer();
        $this->seller = $this->shopping_order->getSeller();

        $subject = sprintf(
          'Regarding order %s (%s)',
          $this->shopping_order->getUuid(), $collectible->getName()
        );

        if ($this->getCollector()->isOwnerOf($collectible))
        {
          $this->pm_form = new ComposeAbridgedPrivateMessageForm(
            $this->seller, $this->buyer ?: $this->shopping_order->getBuyerEmail(),
            $subject, array('attach' => array($collectible))
          );

          return 'Sold';
        }
        else if ($this->getCollector()->isOwnerOf($this->buyer))
        {
          $this->pm_form = new ComposeAbridgedPrivateMessageForm(
            $this->buyer, $this->seller, $subject, array('attach' => $collectible)
          );

          return 'Purchased';
        }
      }

      $this->forward404();
    }

    $this->redirectUnless(
      $collection instanceof CollectorCollection && $this->getCollector()->isOwnerOf($collectible),
      '@mycq_collections'
    );

    if ($request->getParameter('cmd'))
    {
      switch ($request->getParameter('cmd'))
      {
        case 'delete':

          $name = $collectible->getName();

          $url = $this->generateUrl(
            'mycq_collection_by_section', array(
              'id' => $collection->getId(), 'section' => 'collectibles'
            )
          );

          try
          {
            /**
             * If the Collectible has Multimedia associated with it, let's just
             * delete the CollectionCollectible references so that it can return
             * to the Dropbox
             */
            $default = $collectible->getMultimediaCount() > 0 ? 'collections' : 'collectible';

            switch ($request->getParameter('scope', $default))
            {
              case 'collectible':
                // Delete the Collectible
                $collectible->delete();
                $this->getUser()->setFlash('success', sprintf('Item "%s" was deleted!', $name));
                break;
              case 'collection':
                // Delete the CollectionCollectible reference for this collection
                CollectionCollectibleQuery::create()
                  ->filterByCollection($collection)
                  ->filterByCollectible($collectible)
                  ->delete();

                $this->getUser()->setFlash(
                  'success', sprintf('Item "%s" was removed from this Collection!', $name)
                );
                break;
              case 'collections':
              default:
                // Delete the CollectionCollectible references
                CollectionCollectibleQuery::create()
                  ->filterByCollectible($collectible)
                  ->delete();

                $this->getUser()->setFlash(
                  'success', sprintf('Item "%s" was removed from all Collections!', $name)
                );
                break;
            }
          } catch (PropelException $e)
          {
            if (stripos($e->getMessage(), 'a foreign key constraint fails'))
            {
              $this->getUser()->setFlash(
                'error', sprintf(
                  'Collectible "%s" cannot be deleted.
                   Please, try to archive it instead.', $name)
              );

              $url = $this->generateUrl(
                'mycq_collectible_by_slug', array('sf_subject' => $collectible)
              );
            }
          }

          $this->redirect($url);

          break;
      }
    }

    $form = new CollectibleEditForm($collectible);
    $form->setDefault('return_to', $request->getParameter('return_to'));

    if ($request->getParameter('suggest_tags') && !count($collectible->getTags()))
    {
      $form->setDefault('tags', $collection->getTags());
    }

    $form_shipping_us = new SimpleShippingCollectorCollectibleForCountryForm(
      $collectible,
      'US',
      $request->getParameter('shipping_rates_us')
    );
    $form_shipping_zz = new SimpleShippingCollectorCollectibleInternationalForm(
      $collectible,
      $request->getParameter('shipping_rates_zz')
    );

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
      $for_sale = $form->getValue('for_sale');

      if (
        (isset($taintedValues['for_sale']['is_ready']) && $taintedValues['for_sale']['is_ready'])
        && IceGateKeeper::open('collectible_shipping')
      )
      {
        $form_shipping_us->bind($request->getParameter('shipping_rates_us'));
        $form_shipping_zz->bind($request->getParameter('shipping_rates_zz'));
      }

      if (
        ($form_shipping_us->isBound() && $form_shipping_zz->isBound()) &&
        !($form_shipping_us->isValid() && $form_shipping_zz->isValid()) &&
        $form->isValid()
      )
      {
        $this->getUser()->setFlash('error', 'There is a problem with your shipping information.');
      }

      if (
        $form->isValid() &&
        (!$form_shipping_us->isBound() || $form_shipping_us->isValid()) &&
        (!$form_shipping_zz->isBound() || $form_shipping_zz->isValid())
      )
      {
        if ($form_shipping_us->isValid())
        {
          $form_shipping_us->save();
        }
        if ($form_shipping_zz->isValid())
        {
          $form_shipping_zz->save();
        }

        if (
          null !== $for_sale &&
          $for_sale['is_ready'] !== $collectible->getCollectibleForSale()->getIsReady() &&
          $for_sale['is_ready'] === true
        )
        {
          $message = sprintf(
            'Your item item "<a href="%s">%%s</a>" has been posted to the Market!',
            $this->generateUrl('mycq_collectible_by_slug', array('sf_subject' => $collectible))
          );
        }
        else
        {
          $message = sprintf(
            'Changes to your item "<a href="%s">%%s</a>" were saved!',
            $this->generateUrl('mycq_collectible_by_slug', array('sf_subject' => $collectible))
          );
        }

        try
        {
          $form->save();
          $this->getUser()->setFlash('success', sprintf($message, $form->getValue('name')), true);

          // auto-set collection thumbnail if none set yet
          $values = $form->getValues();
          if (isset($values['thumbnail']))
          {
            if (1 == $collection->countCollectibles() && !$collection->hasThumbnail())
            {
              $collection->setPrimaryImage($values['thumbnail']);
              $collection->save();
            }
          }

          // Did the Collector use the "Save and Add Items" button?
          if ($request->getParameter('save_and_go'))
          {
            // If we save the form successfully, the request has to be redirected
            switch ($form->getValue('return_to'))
            {
              case 'market':
                $this->redirect('mycq_marketplace');
                break;
              case 'collection':
              default:
                $this->redirect('mycq_collection_by_slug', $collection);
                break;
            }
          }
        } catch (PropelException $e)
        {
          $this->getUser()->setFlash(
            'error', 'There was a problem saving your information'
          );
        }
      }
      else
      {
        $this->defaults = $taintedValues;
      }
    }

    $c = new Criteria();
    $c->add(CollectionCollectiblePeer::COLLECTIBLE_ID, $collectible->getId(), Criteria::NOT_EQUAL);
    $c->setLimit(10);
    $this->collectibles = $collection->getCollectionCollectibles($c);

    $this->collection = $collection;
    $this->collectible = $collectible;

    $this->form = $form;
    $this->form_for_sale = isset($form['for_sale']) ? $form['for_sale'] : null;
    $this->form_shipping_us = $form_shipping_us;
    $this->form_shipping_zz = $form_shipping_zz;

    if ($collectible->isForSale() || $request->getParameter('available_for_sale') === 'yes')
    {
      SmartMenu::setSelected('mycq_menu', 'marketplace');
    }
    else
    {
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

    // Get the Collector
    $collector = $this->getCollector(true);

    $q = CollectibleForSaleQuery::create()
      ->filterByCollector($collector)
      ->isForSale();
    $this->total = $q->count();

    $q = ShoppingOrderQuery::create()
      ->isPaid()
      ->filterBySellerId($collector->getId());
    $this->sold_total = $q->count();

    // Make the seller available to the template
    $this->seller = $seller;

    // Make the collector available to the template
    $this->collector = $collector;

    // determine weather to show message for incomplete collectibles
    $this->incomplete_collections = false;

    if (IceGateKeeper::open('mycq_incomplete', 'page'))
    {
      $q = CollectibleQuery::create()
        ->filterByCollector($collector)
        ->isPartOfCollection()
        ->isForSale()
        ->isIncomplete();
      if ($q->count() > 0)
      {
        $this->incomplete_collections = true;
      }
    }

    return sfView::SUCCESS;
  }

  public function executeMarketplaceSold()
  {
    SmartMenu::setSelected('mycq_menu', 'marketplace');

    return sfView::SUCCESS;
  }

  public function executeMarketplacePurchased()
  {
    SmartMenu::setSelected('mycq_menu', 'marketplace');

    $q = ShoppingOrderQuery::create()
      ->filterByCollectorId($this->getCollector()->getId())
      ->isPaidOrConfirmed();

    $this->purchases_total = $q->count();

    return sfView::SUCCESS;
  }

  public function executeMarketplaceCreditHistory()
  {
    SmartMenu::setSelected('mycq_menu', 'marketplace');

    $this->package_transactions = PackageTransactionQuery::create()
      ->filterByCollector($this->getCollector())
      ->_if('dev' != sfConfig::get('sf_environment'))
      ->paidFor()
      ->_endif()
      ->find();

    return sfView::SUCCESS;
  }

  public function executeMarketplaceSettings(sfWebRequest $request)
  {
    $this->forward404Unless($this->getCollector()->hasBoughtCredits());

    SmartMenu::setSelected('mycq_menu', 'marketplace');

    $form = new CollectorEditForm($this->getCollector(), array(
      'seller_settings_show'     => true,
      'seller_settings_required' => false,
    ));

    $form->useFields(array(
      'seller_settings_paypal_email',
      'seller_settings_paypal_fname',
      'seller_settings_paypal_lname',
      'seller_settings_phone_number',
      'seller_settings_store_name',
      'seller_settings_store_title',
      'seller_settings_refunds',
      'seller_settings_shipping'
    ));

    if (sfRequest::POST == $request->getMethod())
    {
      if ($form->bindAndSave($request->getParameter($form->getName())))
      {
        $this->getUser()->setFlash(
          'success', 'You have successfully updated your store settings.'
        );

        $save_button = $request->getParameter('save');

        if (isset($save_button['and_add_new_items']))
        {
          if ($return_to = $this->getUser()->getAttribute('purchase_credits_return_to', null, 'seller'))
          {
            $this->getUser()->setAttribute('purchase_credits_return_to', null, 'seller');
          }

          return $this->redirect($return_to ? $return_to : '@mycq_marketplace');
        }

        // always redirect after successful post!
        return $this->redirect($request->getUri());
      }
    }

    $this->collector = $this->getCollector(true);
    $this->form = $form;

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

    if ($total === 0)
    {
      $this->getUser()->setFlash(
        'error', 'There was a problem uploading your photos and none were uploaded'
      );
    }

    $redirect = $request->getReferer()
      ? $request->getReferer()
      : '@mycq_collections';

    $this->redirect($redirect);
  }

  public function executeShoppingOrder()
  {
    SmartMenu::setSelected('mycq_menu', 'marketplace');

    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    $collectible = $shopping_order->getCollectible();
    $this->redirect('mycq_collectible_by_slug', $collectible);
  }

  public function executeShoppingOrderTracking(sfWebRequest $request)
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();

    if ($request->isMethod('post') && $this->getUser()->isOwnerOf($shopping_order))
    {
      if (!$request->getParameter('tracking_number'))
      {
        $this->getUser()->setFlash(
          'error', 'You need to provide the tracking number in order to mark the item as shipped!'
        );
      }
      else if ($shopping_order->getShippingTrackingNumber())
      {
        $this->getUser()->setFlash(
          'error', 'You have already provided the tracking number for this order!'
        );
      }
      else
      {
        $shopping_order->setShippingCarrier($request->getParameter('carrier'));
        $shopping_order->setShippingTrackingNumber($request->getParameter('tracking_number'));
        $shopping_order->save();

        $cqEmail = new cqEmail($this->getMailer());
        $cqEmail->send('shopping/buyer_order_shipped', array(
          'to'      => $shopping_order->getBuyerEmail(),
          'subject' => 'Your order from CollectorsQuest.com has shipped',
          'params'  => array(
            'buyer_name'     => $shopping_order->getShippingFullName(),
            'oCollectible'   => $shopping_order->getCollectible(),
            'oSeller'        => $shopping_order->getSeller(),
            'oShoppingOrder' => $shopping_order
          )
        ));

        $this->getUser()->setFlash('success', sprintf(
          'An email was sent to %s (%s) with the tracking number information',
          $shopping_order->getShippingFullName(), $shopping_order->getBuyerEmail()
        ));
      }

      $this->redirect('mycq_collectible_by_slug', $shopping_order->getCollectible());
    }

    return sfView::SUCCESS;
  }

  public function executeWanted()
  {
    SmartMenu::setSelected('mycq_menu', 'wanted');

    return sfView::SUCCESS;
  }

  public function executeIncomplete()
  {
    $this->forward404Unless(IceGateKeeper::open('mycq_incomplete', 'page'));

    $q = CollectorCollectionQuery::create()
      ->filterByCollector($this->getUser()->getCollector())
      ->isIncomplete();
    if ($q->count() > 0)
    {
      return $this->redirect('@mycq_incomplete_collections');
    }

    $q = CollectibleQuery::create()
      ->filterByCollector($this->getUser()->getCollector())
      ->isPartOfCollection()
      ->isIncomplete();
    if ($q->count() > 0)
    {
      return $this->redirect('@mycq_incomplete_collectibles');
    }

    $this->getUser()->setFlash(
      'success',
      'Great! You do not have any incomplete collections or collectibles.'
    );

    return $this->redirect('@mycq_collections');
  }

  public function executeIncompleteCollections()
  {
    $this->forward404Unless(IceGateKeeper::open('mycq_incomplete', 'page'));

    SmartMenu::setSelected('mycq_menu', 'collections');

    /* @var $q CollectorCollectionQuery */
    $q = CollectorCollectionQuery::create()
      ->filterByCollector($this->getUser()->getCollector())
      ->isIncomplete();

    $this->total = $q->count();

    $pager = new PropelModelPager($q, 18);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();

    $this->pager = $pager;

    return sfView::SUCCESS;
  }

  public function executeIncompleteCollectibles()
  {
    $this->forward404Unless(IceGateKeeper::open('mycq_incomplete', 'page'));

    SmartMenu::setSelected('mycq_menu', 'collections');

    /* @var $q CollectibleQuery */
    $q = CollectibleQuery::create()
      ->filterByCollector($this->getUser()->getCollector())
      ->isPartOfCollection()
      ->isIncomplete();

    $this->total = $q->count();

    $pager = new PropelModelPager($q, 18);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();

    $this->pager = $pager;

    return sfView::SUCCESS;
  }

  /**
   * Action CreatePassword
   *
   * @param sfWebRequest $request
   *
   * @return string
   *
   */
  public function executeCreatePassword(sfWebRequest $request)
  {
    SmartMenu::setSelected('mycq_menu', 'profile');

    $collector = $this->getCollector();

    $this->redirectUnless($collector->getGraphId() && substr($collector->getUsername(), 0, 3) == 'rpx', 'mycq_profile_account_info');

    $collector_form = new CollectorCreatePasswordForm($this->getCollector());

    if (sfRequest::POST == $request->getMethod())
    {
      $success = $collector_form->bindAndSave($request->getParameter($collector_form->getName()));

      if ($success)
      {
        $this->getUser()->setFlash('success',
          'You have successfully updated your profile.');

        $this->redirect('mycq_profile_account_info');
      }
      else
      {
        $this->getUser()->setFlash(
          'error', 'There was an error while updating your profile.
                    Please see below.'
        );
      }
    }

    $this->collector = $collector;
    $this->collector_form = $collector_form;

    return sfView::SUCCESS;
  }

}
