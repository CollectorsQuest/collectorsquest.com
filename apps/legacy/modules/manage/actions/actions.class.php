<?php

/**
 * manage actions.
 *
 * @package    CollectorsQuest
 * @subpackage manage
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class manageActions extends cqActions
{

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeProfile(sfWebRequest $request)
  {
    /**
     * This variable is for us to know which box to make active in the navigation
     */
    $request->setAttribute('header_icons_active', 'profile');

    $collector = $this->getCollector();
    $form = new CollectorEditForm($collector);
    $shipping_rates_form = new ShippingRatesCollectionForm($collector, array(
        'tainted_request_values' => $request->getParameter('shipping_rates_collection'),
    ));

    if ($request->isMethod('post'))
    {
      if ($request->hasParameter('collector'))
      {
        $form->bind($request->getParameter('collector'), $request->getFiles('collector'));
        if ($form->isValid())
        {
          $form->save();

          // Clear the Geo Cache
          CollectorGeocacheQuery::create()->filterByCollectorId($collector->getId())->delete();

          $message = 'Your profile/account information was updated.';
          if ($collectorEmail = $form->getOption('newEmail', false))
          {
            $subject = $this->__('You have changed your email at CollectorsQuest.com');
            $body = $this->getPartial(
              'emails/collector_email_change',
              array(
                'collector'     => $collector,
                'collectorEmail'=> $collectorEmail
              )
            );

            if ($this->sendEmail($form->getValue('email'), $subject, $body))

              $message .= ' Email verification sent to ' . $form->getValue('email');
          }

          $this->getUser()->setFlash('success', $message);

          // Send the profile data to Defensio to analyse
          $collector->sendToDefensio('UPDATE');

          $this->redirect('@manage_profile');
        }
        else
        {
          $this->getUser()->setFlash('error', 'There were some problems, please take a look below.');
        }
      }

      if ($collector->getIsSeller() && $request->hasParameter('shipping_rates_collection'))
      {
        if ($shipping_rates_form->bindAndSave($request->getParameter('shipping_rates_collection')))
        {
          $this->getUser()->setFlash('success', 'Your shipping information was updated');
          $this->redirect('@manage_profile');
        }
        else
        {
          $this->getUser()->setFlash('error', 'There were some problems, please take a look below.');
        }
      }
    }

    // Make the Form and Collector available in the template
    $this->form = $form;
    $this->collector = $collector;
    $this->collector_addresses = $collector->getCollectorAddresss();

    if ($collector->getIsSeller())
    {
      // only make the shipping rates form available to the template if the
      // collecto is a seller
      $this->shipping_rates_form = $shipping_rates_form;
    }

    $this->addBreadcrumb($this->__('Collectors'), '@collectors');
    $this->addBreadcrumb($this->__('Your Profile'));

    $this->prependTitle($this->__('Your Profile'));

    return sfView::SUCCESS;
  }

  /**
   * @param   sfWebRequest  $request
   * @return  string
   */
  public function executeCollections(sfWebRequest $request)
  {
    /**
     * This variable is for us to know which box to make active in the navigation
     */
    $request->setAttribute('header_icons_active', 'collections');

    // Get the collections of the current collector
    $c = new Criteria();
    $c->add(CollectorCollectionPeer::COLLECTOR_ID, $this->getUser()->getId());
    $c->addDescendingOrderByColumn(CollectorCollectionPeer::CREATED_AT);

    $per_page = ($request->getParameter('show') == 'all') ? 999 : sfConfig::get('app_pager_manage_collections_max', 10);

    $pager = new sfPropelPager('CollectorCollection', $per_page);
    $pager->setCriteria($c);
    $pager->setPeerMethod('doSelectJoinCollector');
    $pager->setPeerCountMethod('doCountJoinCollector');

    $page = $request->getParameter('page', 1);
    $pager->setPage($page);
    $pager->init();

    $this->pager = $pager;
    $this->collections = $pager->getResults();
    $this->dropbox = new CollectionDropbox($this->getCollector()->getId());

    $this->bIsSeller = false;
    $ssTitle = $this->__('Your Collections');
    if ($this->getUser()->hasCredential('seller'))
    {
      $this->bIsSeller = true;
      $ssTitle = $this->__('Upload Your Collectibles For Sale');
    }

    if ($this->bIsSeller && count($this->collections) == 0)
    {
      $this->getUser()->setFlash('highlight', 'Create a collection to sell your items. <a href="' . $this->generateUrl('collection_create') . '">Click here</a> to upload your pictures.');
    }
    else if (count($this->collections) == 0)
    {
      $this->getUser()->setFlash('highlight', 'You have no collections yet! <a href="' . $this->generateUrl('collection_create') . '">Click here</a> to create one and start selling items.');
    }

    $this->addBreadcrumb($this->__('Collections'), '@collections');
    $this->addBreadcrumb($ssTitle);

    $this->prependTitle($ssTitle);

    return sfView::SUCCESS;
  }

  public function executeCollection(sfWebRequest $request)
  {
    /**
     * This variable is for us to know which box to make active in the navigation
     */
    $request->setAttribute('header_icons_active', 'collections');

    if ($this->getRoute() instanceof sfPropelRoute)
    {
      $collection = $this->getRoute()->getObject();
    }
    else
    {
      $collection = CollectorCollectionPeer::retrieveByPK($request->getParameter('collection[id]'));
    }
    $this->forward404Unless($collection && $this->getUser()->isOwnerOf($collection));

    if ($request->getParameter('cmd'))
    {
      switch ($request->getParameter('cmd'))
      {
        case 'delete':
          $collection_name = $collection->getName();
          $collection->delete();
          $this->getUser()->setFlash('success', sprintf($this->__('Your collection "%s" was deleted!'), $collection_name));

          return $this->redirect('@manage_collections');
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

          if ($form->getValue('thumbnail'))
          {
            // Set the collection thumbnail from the uploaded file, after we save the collection above
            $collection->setThumbnail($form->getValue('thumbnail')->getTempName());
          }

          $this->getUser()->setFlash("success", $this->__('Changes were saved!'));
          $this->redirect($this->getController()->genUrl(array(
            'sf_route'   => 'manage_collection_by_slug',
            'sf_subject' => $collection,
          )));
        }
        catch (PropelException $e)
        {
          $this->getUser()->setFlash("error", $this->__('There was a problem while saving the information you provided!'));
        }
      }
      else
      {
        $this->defaults = $taintedValues;
        $this->getUser()->setFlash('error', $this->__('There were some problems, please take a look below.'));
      }
    }

    $this->collection = $collection;
    $this->form = $form;

    $this->addBreadcrumb($this->__('Your Collections'), '@manage_collections');
    $this->addBreadcrumb($collection->getName());

    $this->prependTitle($collection->getName());

    return sfView::SUCCESS;
  }

  /**
   * @param  sfWebRequest  $request
   * @return string|void
   */
  public function executeCollectible(sfWebRequest $request)
  {
    /**
     * This variable is for us to know which box to make active in the navigation
     */
    $request->setAttribute('header_icons_active', 'collections');

    if ($this->getRoute() instanceof sfPropelRoute)
    {
      /** @var $collectible Collectible */
      $collectible = $this->getRoute()->getObject();
      $collection = $collectible->getCollection();
    }
    else
    {
      /** @var $collectible Collectible */
      $collectible = CollectiblePeer::retrieveByPK($request->getParameter('id'));
      $collection = $collectible->getCollection();
    }
    $this->forward404Unless($collectible && $this->getUser()->isOwnerOf($collectible));

    if ($request->getParameter('cmd'))
    {
      switch ($request->getParameter('cmd'))
      {
        case 'delete':

          $collectible_name = $collectible->getName();
          $collectible->delete();
          $this->getUser()->setFlash('success', sprintf($this->__('Collectible "%s" was deleted!'), $collectible_name));

          $this->loadHelpers('cqLinks');

          return $this->redirect(route_for_collection($collection));

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
          $this->getUser()->setFlash('success', $this->__('Changes were saved!'));
        }

        // if we save the form the request has to be redirected
        $this->redirect('manage_collectible_by_slug', $form->getObject());
      }
      else
      {
        $this->defaults = $taintedValues;
        $this->getUser()->setFlash("error", $this->__('There was a problem while saving the information you provided!'));
      }
    }

    $this->collection = $collection;
    $this->collectible = $collectible;

    $this->form = $form;
    $this->omItemForSaleForm = isset($omItemForSaleForm) ? $omItemForSaleForm : null;

    $this->loadHelpers('cqLinks');

    $this->addBreadcrumb($this->__('Your Collections'), '@manage_collections');

    $this->addBreadcrumb($collection->getName(), route_for_collection($collection));
    $this->addBreadcrumb($collectible->getName());

    $this->prependTitle($collectible->getName());

    return sfView::SUCCESS;
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeMarketplace(sfWebRequest $request)
  {
    /**
     * This variable is for us to know which box to make active in the navigation
     */
    $request->setAttribute('header_icons_active', 'marketplace');

    $this->collectibles_for_sale = CollectibleForSalePeer::doSelectByCollector($this->getUser()->getCollector(), true);
    $this->collectibles_sold = CollectibleForSalePeer::doSelectByCollector($this->getUser()->getCollector(), false);
    $this->collectibles_buying = CollectibleOfferPeer::doSelectByCollector($this->getUser()->getCollector());
    $this->collectibles_bought = CollectibleOfferPeer::doSelectByCollector($this->getUser()->getCollector(), 'accepted');

    $this->addBreadcrumb($this->__('Your Market'));
    $this->prependTitle($this->__('Your Market'));

    if (!$this->collectibles_for_sale && !$this->collectibles_sold && !$this->collectibles_buying && !$this->collectibles_bought)
    {
      if ($this->getUser()->hasCredential('seller'))
      {
        $this->getUser()->setFlash(
          'highlight', $this->__(
            '%username%, want some fast cash? Sell your collectibles today!',
            array('%username%' => $this->getUser()->getCollector())
          )
        );
      }
      else
      {
        $this->getUser()->setFlash(
          'highlight', sprintf(
            '%s, do you want to expand your collection? <a href="%s">Buy collectibles today!</a>',
            $this->getCollector()->getDisplayName(), $this->generateUrl('marketplace')
          )
        );
      }

      return 'Empty';
    }

    return sfView::SUCCESS;
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeFriends(sfWebRequest $request)
  {
    /**
     * This variable is for us to know which box to make active in the navigation
     */
    $request->setAttribute('header_icons_active', 'friends');

    $collector = $this->getCollector();

    $this->addBreadcrumb($this->__('Your Friends'));
    $this->prependTitle($this->__('Your Friends'));

    $this->friends = $collector->getCollectorFriends();

    return sfView::SUCCESS;
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeCollectibles(sfWebRequest $request)
  {
    $collector = $this->getCollector();
    $this->forward404Unless($collector instanceof Collector);

    /* @var $collection Collection */
    if ($this->getRoute() instanceof sfPropelRoute)
    {
      $collection = $this->getRoute()->getObject();
      $this->forward404Unless($collector->isOwnerOf($collection));
    }
    else
    {
      $collection = new CollectionDropbox($collector->getId());
    }

    $criteria = new Criteria();
    $criteria->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);

    // We we have passed specific Collectibles to edit
    if ($ids = $request->getParameter('ids', null))
    {
      $criteria->add(CollectiblePeer::ID, explode(';', $ids), Criteria::IN);
    }
    else if ($batch = $request->getParameter('batch', null))
    {
      $criteria->add(CollectiblePeer::BATCH_HASH, $batch, Criteria::EQUAL);
    }

    if ($collection->getId())
    {
      $criteria->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::RIGHT_JOIN);
      $criteria->add(CollectionCollectiblePeer::COLLECTION_ID, $collection->getId());
    }
    else
    {
      $criteria->add(CollectiblePeer::COLLECTOR_ID, $collector->getId(), Criteria::EQUAL);
      $criteria->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN);
      $criteria->add(CollectionCollectiblePeer::COLLECTION_ID, null, Criteria::ISNULL);
    }

    $pager = new sfPropelPager('Collectible', sfConfig::get('app_collectibles_edit_per_page', 5));
    $pager->setPage($request->getParameter('page', 1));
    $pager->setCriteria($criteria);
    $pager->init();

    if (!$pager->getNbResults())
    {
      $this->redirect('@manage_collections');
    }

    $collectibles = new PropelObjectCollection($pager->getResults());

    $form = new ManageCollectiblesForm(
      $collectibles,
      array(
        'collector'           => $this->getUser()->getCollector(),
        'embedded_form_class' => 'CollectibleEditForm'
      )
    );

    if ($request->isMethod('post'))
    {
      $form->bind(
        $request->getParameter($form->getName()),
        $request->getFiles($form->getName())
      );

      if ($form->isValid())
      {
        $collector = $this->getUser()->getCollector();
        try
        {
          foreach ($form->getValues() as $value)
          {
            $collectible = CollectiblePeer::retrieveByPK($value['id']);
            $collectible->setCollectionId($value['collection_id']);
            $collectible->setName($value['name']);
            $collectible->setDescription($value['description'], 'html');
            $collectible->setTags(is_array($value['tags']) ? implode(', ', $value['tags']) : $value['tags']);
            $collectible->clearCollectibleForSales();
            // handle collectible-collection M:M relation
            $collectible->setCollections(CollectionQuery::create()
                  ->filterById($value['collection_collectible_list'], Criteria::IN)
                  ->find()
            );
            $collectible->save();

            if ($value['thumbnail'])
            {
              $collection = $collectible->getCollection();

              if (!$collection->hasThumbnail())
              {
                $collection->setThumbnail($value['thumbnail']->getTempName());
                $collection->save();
              }

              $collectible->addMultimedia($value['thumbnail'], true);
            }

            if ($value['for_sale']['is_ready'] || $value['for_sale']['price'] || !is_null($value['for_sale']['id']))
            {
              if (empty($value['for_sale']['id']) or !$collectibleForSale = CollectibleForSalePeer::retrieveByPK($value['for_sale']['id']))
              {
                if (empty($value['for_sale']['price']))
                {
                  continue;
                }
                $collectibleForSale = new CollectibleForSale();
                $collectibleForSale->setCollectible($collectible);
              }

              $collectibleForSale->fromArray($value['for_sale'], BasePeer::TYPE_FIELDNAME);
              $collectibleForSale->save();
            }

            if (isset($value['for_sale']) && $value['for_sale']['is_ready'])
            {
              $collector->setItemsAllowed($collector->getItemsAllowed() - 1);
              $collector->save();
            }
          }
        }
        catch (PropelException $e)
        {
          // currently just skip the errors
        }

        $this->getUser()->setFlash('success', 'Collectible data saved');

        if ($pager->isLastPage())
        {
          $this->loadHelpers('cqLinks');

          $this->redirect(url_for_collection($collection));
        }
        else
        {
          $this->redirect(
            'manage_collectibles_by_slug', array(
              'id'    => $collection->getId(),
              'slug'  => $collection->getSlug(),
              'page'  => $pager->getNextPage(),
              'batch' => $request->getParameter('batch'),
              'ids'   => $request->getParameter('ids')
            )
          );
        }
      }
      else
      {
        $this->getUser()->setFlash('error', 'Some fields are invalid');
      }
    }

    $this->collection = $collection;
    $this->form = $form;

    $this->addBreadcrumb($collection->getName(), '@manage_collections');
    $this->addBreadcrumb($this->__('Manage Collectibles'));

    $this->prependTitle($collection->getName());

    return sfView::SUCCESS;
  }

  public function executeDropbox(sfWebRequest $request)
  {
    $collector = $this->getCollector();
    $this->forward404Unless($collector instanceof Collector);

    switch ($request->getParameter('cmd'))
    {
      case 'empty':
        $q = CollectibleQuery::create()
            ->filterByCollectorId($collector->getId())
            ->filterByCollectionId(null, Criteria::ISNULL);

        $q->delete();

        $this->getUser()->setFlash('success', 'Your dropbox was emptied!', true);
        break;
    }

    return $this->redirect('@manage_collections');
  }

  public function executeShoppingOrders()
  {
    $q = ShoppingOrderQuery::create()
       ->filterByCollector($this->getCollector());

    $this->shopping_orders = $q->find();

    return sfView::SUCCESS;
  }

  public function executeShoppingOrder()
  {
    /** @var $shopping_order ShoppingOrder */
    $shopping_order = $this->getRoute()->getObject();
    $this->forward404Unless($this->getCollector()->isOwnerOf($shopping_order));

    return sfView::SUCCESS;
  }

  /**
   * Action ResendEmailChange
   *
   * @param sfWebRequest $request
   *
   */
  public function executeResendEmailChange(sfWebRequest $request)
  {
    /* @var $collectorEmail CollectorEmail */
    $collectorEmail = $this->getCollector()->getLastEmailChangeRequest();
    $this->forward404Unless($collectorEmail instanceof CollectorEmail);

    $collector = $collectorEmail->getCollector();

    $subject = $this->__('You have changed your email at CollectorsQuest.com');
    $body = $this->getPartial(
      'emails/collector_email_change',
      array(
        'collector'     => $collector,
        'collectorEmail'=> $collectorEmail
      )
    );

    if ($this->sendEmail($collectorEmail->getEmail(), $subject, $body))
    {
      $this->getUser()->setFlash('success', 'Email verification sent to ' . $collectorEmail->getEmail());
    }
    else
    {
      $this->getUser()->setFlash('error', 'Invalid email');
    }

    $this->redirect('@manage_profile');
  }

  public function executeAddNewAddress(sfWebRequest $request)
  {
    $address = new CollectorAddress();
    $address->setCollector($this->getCollector());
    $form = new FrontendCollectorAddressForm($address);

    if (sfRequest::POST == $request->getMethod())
    {
      if ($form->bindAndSave($request->getParameter($form->getName())))
      {
        $this->redirect('@manage_profile#collector-marketplace');
      }
    }
    $this->form = $form;

    $this->addBreadcrumb($this->__('Collectors'), '@collectors');
    $this->addBreadcrumb($this->__('Your Profile'), '@manage_profile');
    $this->addBreadcrumb($this->__('Add a new address'));

    $this->prependTitle($this->__('Add a New Address'));

    return sfView::SUCCESS;
  }

  public function executeEditAddress(sfWebRequest $request)
  {
    $address = $this->getRoute()->getObject();
    $this->forward404Unless($this->getUser()->isOwnerOf($address));

    $form = new FrontendCollectorAddressForm($address);

    if (sfRequest::POST == $request->getMethod())
    {
      if ($form->bindAndSave($request->getParameter($form->getName())))
      {
        $this->redirect('@manage_profile#collector-marketplace');
      }
    }

    $this->form = $form;

    $this->addBreadcrumb($this->__('Collectors'), '@collectors');
    $this->addBreadcrumb($this->__('Your Profile'), '@manage_profile');
    $this->addBreadcrumb($this->__('Edit address'));

    $this->prependTitle($this->__('Edit Address'));

    return sfView::SUCCESS;
  }

  public function executeDeleteAddress(sfWebRequest $request)
  {
    $address = $this->getRoute()->getObject();
    $this->forward404Unless($this->getUser()->isOwnerOf($address));

    if (sfRequest::DELETE == $request->getMethod())
    {
      $address->delete();
      $this->getUser()->setFlash('success',
        $this->__('You have successfully removed an address from your account.'));

      return $this->redirect('@manage_profile#collector-marketplace');
    }

    $this->collector_address = $address;

    $this->addBreadcrumb($this->__('Collectors'), '@collectors');
    $this->addBreadcrumb($this->__('Your Profile'), '@manage_profile');
    $this->addBreadcrumb($this->__('Confirm address deletion'));

    $this->prependTitle($this->__('Confirm Address Deletion'));

    return sfView::SUCCESS;
  }

}
