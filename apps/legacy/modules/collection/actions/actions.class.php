<?php

class collectionActions extends cqActions
{

  /**
   * @param sfWebRequest $request
   * @return string
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->getRoute() instanceof sfPropelRoute);

    /** @var $object BaseObject */
    $object = $this->getRoute()->getObject();

    if ($object instanceof Collector)
    {
      /** @var $collector Collector */
      $collector = $object;

      /** @var $collection CollectionDropbox */
      $collection = $collector->getCollectionDropbox();
    }
    else
    {
      /** @var $collection CollectorCollection */
      $collection = $object;

      /** @var $collector Collector */
      $collector  = $collection->getCollector();
    }

    // Is the current Collector the owner of the collection?
    $editable = $this->getCollector()->isOwnerOf($collection);

    if (!$editable)
    {
      $collection->setNumViews($collection->getNumViews() + 1);
      $collection->save();
    }

    $c = new Criteria();
    $c->add(CollectiblePeer::COLLECTOR_ID, $collection->getCollectorId());

    if ($collection instanceof CollectionDropbox)
    {
      $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::LEFT_JOIN);
      $c->add(CollectionCollectiblePeer::COLLECTION_ID, null, Criteria::ISNULL);
    }
    else
    {
      $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID);
      $c->add(CollectionCollectiblePeer::COLLECTION_ID, $collection->getId());
    }

    $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addAscendingOrderByColumn(CollectiblePeer::CREATED_AT);

    $per_page = sfConfig::get('app_pager_list_collectibles_max', 16);

    if ($collection instanceof CollectionDropbox)
    {
      $pager = new cqPropelPager('Collectible', $per_page);
    }
    else
    {
      $pager = new cqPropelPager('CollectionCollectible', $per_page);
    }
    $pager->setCriteria($c);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    $this->pager      = $pager;
    $this->display    = $this->getUser()->getAttribute('display', 'grid', 'collectibles');
    $this->collector  = $collector;
    $this->collection = $collection;
    $this->editable   = $editable;

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Community'), '@community');
    $this->addBreadcrumb(
      $collector->getDisplayName(),
      '@collections_by_collector?collector='. $collector->getSlug()
    );
    $this->addBreadcrumb($this->__('Collections'), '@collections');

    if ($collection instanceof CollectionDropbox)
    {
      if ($editable)
      {
        $this->addBreadcrumb('Your Dropbox', null);
      }
      else
      {
        $this->addBreadcrumb('The Dropbox of '. $collector->getDisplayName(), null);
      }
    }
    else
    {
      $this->addBreadcrumb(
        $collection->getName(), null,
        array(
          'id' => 'collection_'. $collection->getId() .'_name',
          'class' => $editable ? 'editable_h1' : null
        )
      );
    }

    // Building the title
    $this->prependTitle($collector->getDisplayName());
    $this->prependTitle($collection->getName());

    // Building the meta tags
    $this->getResponse()->addMeta('description', $collection->getDescription('stripped'));
    $this->getResponse()->addMeta('keywords', $collection->getTagString());

    // Building the geo.* meta tags
    $this->getResponse()->addGeoMeta($collection->getCollector());

    // Setting the Canonical URL
    $this->loadHelpers(array('cqLinks'));
    $this->getResponse()->setCanonicalUrl(url_for_collection($collection, true));

    if ($collection->countCollectibles() == 0 || $pager->getLastPage() < $this->getRequestParameter('page', 1))
    {
      $this->collections = null;

      if (!($collection instanceof CollectionDropbox) && !$editable)
      {
        $c = new Criteria();
        $c->add(CollectionPeer::IS_PUBLIC, true);
        if ($collection->getCollectionCategoryId())
        {
          $c->add(CollectionPeer::COLLECTION_CATEGORY_ID, $collection->getCollectionCategoryId());
        }
        $c->add(CollectionPeer::NUM_ITEMS, 4, Criteria::GREATER_EQUAL);
        $c->addAscendingOrderByColumn(CollectionPeer::SCORE);
        $c->addDescendingOrderByColumn(CollectionPeer::CREATED_AT);
        $c->setLimit(9);

        $this->collections = CollectionPeer::doSelect($c);
      }

      return 'NoCollectibles';
    }

    return sfView::SUCCESS;
  }

  public function executeCollectible()
  {
    /** @var $collectible Collectible|CollectionCollectible */
    $collectible = $this->getRoute()->getObject();

    /** @var $collection Collection */
    $collection = $collectible->getCollection();

    if ($collectible instanceof CollectionCollectible)
    {
      $collectible = $collectible->getCollectible();
    }

    /** @var $collector Collector */
    $collector = $collectible->getCollector();

    /**
     * Special checks for the Collectibles of A&E
     */
    $pawn_stars = sfConfig::get('app_aent_pawn_stars');
    $american_pickers = sfConfig::get('app_aent_american_pickers');

    if (in_array($collection->getId(), array($pawn_stars['collection'], $american_pickers['collection'])))
    {
      $this->redirect('@aent_collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug(), 301);
    }

    /**
     * Figure out the previous and the next item in the collection
     */
    $collectible_ids = $collection->getCollectibleIds();

    if (array_search($collectible->getId(), $collectible_ids) - 1 < 0)
    {
      $q = CollectionCollectibleQuery::create()
         ->filterByCollection($collection)
         ->filterByCollectibleId($collectible_ids[count($collectible_ids) - 1]);
      $this->previous = $q->findOne();
    }
    else
    {
      $q = CollectionCollectibleQuery::create()
         ->filterByCollection($collection)
         ->filterByCollectibleId($collectible_ids[array_search($collectible->getId(), $collectible_ids) - 1]);
      $this->previous = $q->findOne();
    }

    if (array_search($collectible->getId(), $collectible_ids) + 1 >= count($collectible_ids))
    {
      $q = CollectionCollectibleQuery::create()
         ->filterByCollection($collection)
         ->filterByCollectibleId($collectible_ids[0]);
      $this->next = $q->findOne();
    }
    else
    {
      $q = CollectionCollectibleQuery::create()
         ->filterByCollection($collection)
         ->filterByCollectibleId($collectible_ids[array_search($collectible->getId(), $collectible_ids) + 1]);
      $this->next = $q->findOne();
    }

    $this->collector = $collector;
    $this->collectible = $collectible;
    $this->additional_multimedia = $collectible->getMultimedia(false);

    $c = new Criteria();
    $c->add(CollectionCollectiblePeer::COLLECTIBLE_ID, $collectible->getId(), Criteria::NOT_EQUAL);
    $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addAscendingOrderByColumn(CollectionCollectiblePeer::CREATED_AT);
    $this->collectibles = $collection->getCollectibles($c);

    $this->loadHelpers('cqLinks');

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Collections'), '@collections');
    $this->addBreadcrumb( $collection->getName(), route_for_collection($collection), array('limit' => 38));
    $this->addBreadcrumb(
      $collectible->getName(), null,
      array(
        'id' => 'collectible_'.$collectible->getId().'_name',
        'class' => ($this->getCollector()->isOwnerOf($collectible)) ? 'editable_h1' : ''
      )
    );

    // Building the title
    $this->prependTitle($collection->getName());
    $this->prependTitle($collectible->getName());

    // Building the meta tags
    $this->getResponse()->addMeta('description', $collectible->getDescription('stripped'));
    $this->getResponse()->addMeta('keywords', $collectible->getTagString());

    // Building the geo.* meta tags
    $this->getResponse()->addGeoMeta($collection->getCollector());

    // Setting the Canonical URL
    $this->loadHelpers(array('cqLinks'));
    $this->getResponse()->setCanonicalUrl(url_for_collectible($collectible, true));

    return sfView::SUCCESS;
  }

  public function executeCreate(sfWebRequest $request)
  {
    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Community'), '@community');
    $this->addBreadcrumb($this->__('Collections'), '@collections');
    $this->addBreadcrumb($this->__("Add Your Collection to Collectors' Quest"));

    switch ($request->getParameter('step', 1))
    {
      case 1:
      default:

        $q = CollectionCategoryQuery::create()
           ->filterByParentId(0)
           ->filterByName(array('None', 'Other'), Criteria::NOT_IN)
           ->orderBy('Name');
        $categories = $q->find();
        $categories[] = CollectionCategoryQuery::create()->findOneById(35);

        $this->categories = IceFunctions::array_vertical_sort($categories, 3);

        return 'Step1';
        break;
      case 2:
        $collection_category = CollectionCategoryPeer::retrieveByPK($request->getParameter('collection_category_id'));
        $this->forward404Unless($collection_category instanceof CollectionCategory);

        $form = new CollectorCollectionCreateForm();
        if ($request->isMethod('post'))
        {
          $form->bind($request->getParameter($form->getName()),
                      $request->getFiles($form->getName()));

          if ($form->isValid())
          {
            $collection = new CollectorCollection();
            $collection->setCollector($this->getUser()->getCollector());
            $collection->setCollectionCategory($collection_category);
            $collection->setName($form->getValue('name'));
            $collection->setDescription($form->getValue('description'), 'html');
            $collection->setTags($form->getValue('tags'));

            try
            {
              $collection->save();

              // Set the collection thumbnail from the uploaded file, after we save the collection above
              $collection->setThumbnail($form->getValue('thumbnail')->getTempName());

              $collector = $this->getUser()->getCollector();
              if ($collector->getProfile()->getIsImageAuto())
              {
                $collector->setPhoto($collection->getThumbnail()->getSource());

                $profile = $collector->getProfile();
                $profile->setIsImageAuto(false);
                $profile->save();
              }

              $this->redirect('@collection_create?step=3&collection_id='. $collection->getId());
            }
            catch (PropelException $e)
            {
              $this->getUser()->setFlash("error", $this->__('There was a problem saving the information you provided!'));
            }
          }
          else
          {
            $this->getUser()->setFlash('error', 'There were some problems, please take a look below.');
          }
        }

        $this->collection_category = $collection_category;
        $this->form = $form;

        return 'Step2';
        break;
      case 3:
        $this->collection = CollectorCollectionPeer::retrieveByPK($request->getParameter('collection_id'));

        return 'Step3';
        break;
    }
  }

  public function executeRemoveItem()
	{
    /* @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();
    $this->forward404Unless($collectible instanceof Collectible);

    $collectibleForSale = $collectible->getCollectibleForSale();
    $this->forward404Unless($collectibleForSale instanceof CollectibleForSale);

    $collectibleForSale->delete();

    $this->getUser()->setFlash('notice', 'Collectibe removed from market');

		$this->redirect('@manage_marketplace');
	}

}
