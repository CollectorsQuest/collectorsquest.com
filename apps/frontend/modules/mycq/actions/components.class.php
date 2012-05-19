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

    $pager = new PropelModelPager($q, 7);
    $pager->setPage($this->getRequestParameter('p', 1));
    $pager->init();
    $this->pager = $pager;

    return sfView::SUCCESS;
  }

  public function executeDropbox()
  {
    $collector = $this->getCollector();
    $dropbox = $collector->getCollectionDropbox();

    $this->collectibles = $dropbox->getCollectibles();
    $this->total = $dropbox->countCollectibles();

    return $this->total > 0 ? sfView::SUCCESS : sfView::NONE;
  }

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
}
