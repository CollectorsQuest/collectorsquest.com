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
    $this->collector = $this->getVar('collector') ?: $this->getUser()->getCollector();

    $q = CollectorCollectionQuery::create()
      ->filterByCollector($this->collector)
      ->orderByCreatedAt(Criteria::DESC);

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

<<<<<<< Updated upstream
  public function executeCollectibles()
  {
    /** @var $collection CollectorCollection */
    $collection = $this->getVar('collection');

    // Let's make sure the current user is the owner
    if (!$this->getUser()->isOwnerOf($collection))
    {
      return sfView::SUCCESS;
    }

    $q = CollectionCollectibleQuery::create()
      ->filterByCollection($collection)
      ->orderByPosition(Criteria::ASC)
      ->orderByCreatedAt(Criteria::DESC);

    if ($this->getRequestParameter('q'))
    {
      $q->search($this->getRequestParameter('q'));
    }

    $pager = new PropelModelPager($q, 11);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();

    $this->pager = $pager;
    $this->collection = $collection;

    return sfView::SUCCESS;
  }

=======
>>>>>>> Stashed changes
  public function executeDropbox()
  {
    $collector = $this->getCollector();
    $dropbox = $collector->getCollectionDropbox();

<<<<<<< Updated upstream
    $this->batch = cqStatic::getUniqueId(32);
=======
>>>>>>> Stashed changes
    $this->collectibles = $dropbox->getCollectibles();
    $this->total = $dropbox->countCollectibles();

    return sfView::SUCCESS;
  }
<<<<<<< Updated upstream

  public function executeCreateCollection()
  {
    $form = new CollectionCreateForm();

    if ($this->getRequest()->isMethod('post'))
    {
      $form->bind($this->getRequestParameter('collection'));
      if ($form->isValid())
      {
        $values = $form->getValues();
        $values['collector_id'] = $this->getCollector()->getId();

        /** @var $collection CollectorCollection */
        $collection = $form->updateObject($values);
        $collection->setTags($values['tags']);
        $collection->save();

        $this->collection = $collection;
      }
      else
      {
        // @todo: Here we need to add ModelCriteria filterByTags
        // $form->getWidget('content_category_id')->setOption('', '');
      }
    }
    else
    {
      unset($form['content_category_id']);
    }

    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeCreateCollectible()
  {
    $form = new CollectibleCreateForm();
    $form->setDefault('collection_id', $this->getRequestParameter('collection_id'));

    if ($this->getRequest()->isMethod('post'))
    {
      $form->bind($this->getRequestParameter('collectible'));

      if ($form->isValid())
      {
        $values = $form->getValues();

        $collection = CollectorCollectionQuery::create()
          ->findOneById($values['collection_id']);

        if (!$this->getCollector()->isOwnerOf($collection)) {
          return sfView::NONE;
        }

        $values = $form->getValues();
        $values['collector_id'] = $this->getCollector()->getId();

        /** @var $collectible Collectible */
        $collectible = $form->updateObject($values);
        $collectible->setTags($values['tags']);
        $collectible->save();

        $collectible->addCollection($collection);
        $collectible->save();

        $this->collectible = $collectible;
      }
    }

    $this->form = $form;

    return sfView::SUCCESS;
  }
=======
>>>>>>> Stashed changes
}
