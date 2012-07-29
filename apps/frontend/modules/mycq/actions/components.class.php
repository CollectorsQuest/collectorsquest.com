<?php

class mycqComponents extends cqFrontendComponents
{

  public function executeNavigation()
  {
    $this->collector = $this->getUser()->getCollector();

    $this->module = $this->getModuleName();
    $this->action = $this->getActionName();

    return sfView::SUCCESS;
  }

  public function executeCollectorSnapshot()
  {
    $this->collector = $this->getUser()->getCollector();
    $this->profile = $this->collector->getProfile();

    return sfView::SUCCESS;
  }

  public function executeSellerSnapshot()
  {
    $this->seller = $this->getUser()->getCollector();
    $this->profile = $this->collector->getProfile();

    return sfView::SUCCESS;
  }

  public function executeCollections()
  {
    $this->collector = $this->getVar('collector') ? : $this->getUser()->getCollector();
    $sort = $this->getRequestParameter('s', 'most-recent');

    $q = CollectorCollectionQuery::create()
        ->filterByCollector($this->collector);

    switch ($sort)
    {
      case 'most-relevant':
        //TODO: Order by most-relevant
        break;

      case 'most-recent':
      default:
        $q
          ->useCollectionQuery()
            ->joinCollectionCollectible('Collectible', Criteria::LEFT_JOIN)
            ->addDescendingOrderByColumn('MAX(IF(Collectible.created_at IS NULL, NOW(), Collectible.created_at))')
          ->endUse()
          ->groupById()
          ->orderByCreatedAt(Criteria::DESC);
        break;
    }

    if ($this->getRequestParameter('q'))
    {
      $q->search($this->getRequestParameter('q'));
    }

    $pager = new PropelModelPager($q, 11);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();
    $this->pager = $pager;

    return sfView::SUCCESS;
  }

  public function executeCollectibles()
  {
    /** @var $collection CollectorCollection */
    if ($this->getVar('collection'))
    {
      $collection = $this->getVar('collection');
    }
    else
    {
      $collection = CollectorCollectionQuery::create()
        ->findOneById($this->getRequestParameter('collection_id'));
    }

    // Let's make sure the current user is the owner
    if (!$this->getUser()->isOwnerOf($collection))
    {
      return sfView::SUCCESS;
    }

    $q = CollectionCollectibleQuery::create()
        ->filterByCollection($collection);

    switch ($this->getRequestParameter('s', 'position'))
    {
      case 'most-popular':
        $q
            ->joinCollection()
            ->useCollectionQuery()
            ->orderByNumViews(Criteria::DESC)
            ->endUse();
        break;

      case 'most-recent':
        $q
            ->orderByCreatedAt(Criteria::DESC);
        break;

      case 'position':
      default:
        $q
            ->orderByPosition(Criteria::ASC)
            ->orderByCreatedAt(Criteria::DESC);
        break;
    }

    if ($this->getRequestParameter('q'))
    {
      $q->search($this->getRequestParameter('q'));
    }

    $pager = new PropelModelPager($q, 17);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();

    $this->pager = $pager;
    $this->collection = $collection;

    return sfView::SUCCESS;
  }

  public function executeCollectiblesForSale()
  {
    $collector = $this->getCollector();

    $q = CollectibleForSaleQuery::create()
        ->filterByCollector($collector)
        ->isForSale();

    switch ($this->getRequestParameter('s', 'most-recent'))
    {
      case 'most-popular':
        $q
          ->joinCollectible()
          ->useCollectibleQuery()
          ->orderByNumViews(Criteria::DESC)
          ->endUse();
        break;

      case 'most-recent':
      default:
        $q
          ->orderByCreatedAt(Criteria::DESC);
        break;
    }

    if ($this->getRequestParameter('q'))
    {
      $q->search($this->getRequestParameter('q'));
    }

    $pager = new PropelModelPager($q, 11);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();

    $this->pager = $pager;
    $this->collector = $collector;
    $this->seller = $this->getVar('seller') ?: $this->getUser()->getSeller(true);

    return sfView::SUCCESS;
  }

  public function executeCollectiblesForSaleSold()
  {
    $collector = $this->getCollector();

    $q = CollectibleForSaleQuery::create()
      ->filterByCollector($collector)
      ->filterByIsSold(true)
      ->orderByCreatedAt(Criteria::DESC);

    if ($this->getRequestParameter('q'))
    {
      $q->search($this->getRequestParameter('q'));
    }

    $pager = new PropelModelPager($q, 11);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();

    $this->pager = $pager;
    $this->collector = $collector;

    return sfView::SUCCESS;
  }

  public function executeCollectiblesForSalePurchased()
  {
    $collector = $this->getCollector();

    $q = ShoppingOrderQuery::create()
      ->filterByCollectorId($collector->getId())
      ->orderByCreatedAt(Criteria::DESC);

    if ($this->getRequestParameter('q'))
    {
      $q->search($this->getRequestParameter('q'));
    }

    $pager = new PropelModelPager($q, 11);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();

    $this->pager = $pager;
    $this->collector = $collector;

    return sfView::SUCCESS;
  }

  public function executeDropbox()
  {
    $collector = $this->getCollector();
    $dropbox = $collector->getCollectionDropbox();

    $this->collectibles = $dropbox->getCollectibles();
    $this->total = $dropbox->countCollectibles();

    return sfView::SUCCESS;
  }

  public function executeCreateCollectible()
  {
    $form = new CollectibleCreateForm();

    $q = CollectorCollectionQuery::create()
      ->filterById($this->getRequestParameter('collection_id'));

    if ($collection = $q->findOne())
    {
      $form->setDefault('collection_id', $collection->getId());
      $form->setDefault('tags', $collection->getTags());
    }

    if ($collectible_id = $this->getRequestParameter('collectible_id'))
    {
      $q = CollectibleQuery::create()
          ->filterByCollector($this->getCollector())
          ->filterById($collectible_id);

      /** @var $image iceModelMultimedia */
      if (($collectible = $q->findOne()) && $image = $collectible->getPrimaryImage())
      {
        $form->setDefault('thumbnail', $image->getId());
      }
    }

    if ($this->getRequest()->isMethod('post'))
    {
      $form->bind($this->getRequestParameter('collectible'));

      if ($form->isValid())
      {
        $values = $form->getValues();

        $collection = CollectorCollectionQuery::create()
            ->findOneById($values['collection_id']);

        if (!$this->getCollector()->isOwnerOf($collection))
        {
          return sfView::NONE;
        }

        $values = $form->getValues();
        $values['collector_id'] = $this->getCollector()->getId();

        /** @var $collectible Collectible */
        $collectible = $form->updateObject($values);
        $collectible->setDescription($values['description'], 'html');
        $collectible->setTags($values['tags']);
        $collectible->save();

        $collectible->addCollection($collection);
        $collectible->save();

        if ($values['thumbnail'])
        {
          $image = iceModelMultimediaQuery::create()
              ->findOneById((integer) $values['thumbnail']);

          if ($this->getCollector()->isOwnerOf($image))
          {
            $collectible->setThumbnail($image->getAbsolutePath('original'));
            $collectible->save();
          }
        }

        $this->collectible = $collectible;
      }
    }

    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeCreateCollectibleForSale()
  {
    $form = new CollectibleForSaleCreateForm();

    if ($collectible_id = $this->getRequestParameter('collectible_id'))
    {
      $q = CollectibleQuery::create()
         ->filterByCollector($this->getCollector())
         ->filterById($collectible_id);

      /** @var $image iceModelMultimedia */
      if (($collectible = $q->findOne()) && $image = $collectible->getPrimaryImage())
      {
        $form->setDefault('collectible', array('thumbnail' => $image->getId()));
      }
    }

    if ($this->getRequest()->isMethod('post'))
    {
      $tainted = $this->getRequestParameter('collectible_for_sale');

      /**
       * The logic below is to support creating of new CollectorCollections
       * if the select option's value is not numeric but a string, this
       * shows us that we need to create the collection rather than use
       * an already existing CollectorCollection
       */
      if (!empty($tainted['collectible']['collection_collectible_list']))
      {
        $collection_collectible_list = &$tainted['collectible']['collection_collectible_list'];
        foreach ($collection_collectible_list as $i => $id)
        {
          if (!is_numeric($id) && !empty($id))
          {
            $collection = new CollectorCollection();
            $collection->setCollector($this->getCollector());
            $collection->setName($id);

            try
            {
              $collection->save();
              $collection_collectible_list[$i] = $collection->getId();
            }
            catch (PropelException $e)
            {
              ;
            }
          }
        }
      }

      $form->bind($tainted);

      if ($form->isValid())
      {
        $values = $form->getValues();
        $values['collector_id'] = $this->getCollector()->getId();

        /** @var $collectible_for_sale CollectibleForSale */
        $collectible_for_sale = $form->updateObject($values);

        /** @var $collectible Collectible */
        $collectible = $collectible_for_sale->getCollectible();

        $collectible->setTags($values['collectible']['tags']);
        $collectible->save();

        if ($values['collectible']['thumbnail'])
        {
          $image = iceModelMultimediaQuery::create()
              ->findOneById((integer) $values['collectible']['thumbnail']);

          if ($this->getCollector()->isOwnerOf($image))
          {
            $collectible->setThumbnail($image->getAbsolutePath('original'));
            $collectible->save();
          }
        }

        $this->collectible = $collectible;
      }
      else
      {
        ;
      }
    }

    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeDeleteCollectionCollectible()
  {
    if ($this->getRequest()->isMethod('post'))
    {

    }

    return sfView::SUCCESS;
  }

  public function executeCollectionMultimedia()
  {
    $q = CollectorCollectionQuery::create()
      ->filterById((integer) $this->getRequestParameter('collection_id'));
    $this->collection = $this->getVar('collection') ?: $q->findOne();

    // Stop right here if the CollectorCollection is not accessible
    if (!$this->getUser()->isOwnerOf($this->collection))
    {
      return sfView::NONE;
    }

    if ($this->image = $this->collection->getThumbnail())
    {
      $this->aviary_hmac_message = $this->getUser()->hmacSignMessage(
        json_encode(array('multimedia-id' => $this->image->getId())),
        cqConfig::getCredentials('aviary', 'hmac_secret')
      );
    }

    return sfView::SUCCESS;
  }

  public function executeCollectibleMultimedia()
  {
    $q = CollectibleQuery::create()
      ->filterById((integer) $this->getRequestParameter('collectible_id'));
    $this->collectible = $this->getVar('collectible') ?: $q->findOne();

    // Stop right here if the Collectible is not accessible
    if (!$this->getUser()->isOwnerOf($this->collectible))
    {
      return sfView::NONE;
    }

    $this->multimedia = $this->collectible->getMultimedia(0, 'image', false);

    return sfView::SUCCESS;
  }
}
