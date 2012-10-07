<?php

class aetnActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header', 'collections');
  }

  public function executeIndex()
  {
    $this->redirect('@aetn_landing', 302);
  }

  public function executeLanding()
  {
    $category = ContentCategoryQuery::create()->findOneBySlug('history-militaria'); // History-and-militaria

    if ($category)
    {
      $this->redirect('content_category', $category, 301);
    }
    else
    {
      $this->redirect('homepage');
    }
  }

  public function executeAmericanPickers()
  {
    $american_pickers = sfConfig::get('app_aetn_american_pickers');

    $collection = CollectorCollectionQuery::create()->findOneById($american_pickers['collection']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collection))
    {
      $collection->setNumViews($collection->getNumViews() + 1);
      $collection->save();
    }

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollectionId($american_pickers['collection'])
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);
    $this->collectibles = $q->find();

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarAmericanPickers');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    return sfView::SUCCESS;
  }

  public function executeAmericanRestoration(sfWebRequest $request)
  {
    // Check if the page is publicly available yet
    $this->forward404Unless(IceGateKeeper::open('aetn_american_restoration', 'page'));

    /** @var $aetn_shows array */
    $aetn_shows = sfConfig::get('app_aetn_shows');

    $collection = CollectorCollectionQuery::create()
      ->findOneById($aetn_shows['american_restoration']['collection']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collection))
    {
      $collection->setNumViews($collection->getNumViews() + 1);
      $collection->save();
    }

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollection($collection)
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 9);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    $this->collection = $collection;

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarAmericanRestoration');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    return sfView::SUCCESS;
  }

  public function executePawnStars(sfWebRequest $request)
  {
    $pawn_stars = sfConfig::get('app_aetn_pawn_stars');

    $collection = CollectorCollectionQuery::create()->findOneById($pawn_stars['collection']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collection))
    {
      $collection->setNumViews($collection->getNumViews() + 1);
      $collection->save();
    }

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollectionId($pawn_stars['collection'])
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 9);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarPawnStars');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    return sfView::SUCCESS;
  }

  public function executePickedOff(sfWebRequest $request)
  {
    // Check if the page is publicly available yet
    $this->forward404Unless(IceGateKeeper::open('aetn_picked_off', 'page'));

    $picked_off = sfConfig::get('app_aetn_picked_off');

    $collection = CollectorCollectionQuery::create()->findOneById($picked_off['collection']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collection))
    {
      $collection->setNumViews($collection->getNumViews() + 1);
      $collection->save();
    }

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollectionId($picked_off['collection'])
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 9);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    $this->collection = $collection;

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarPickedOff');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    return sfView::SUCCESS;
  }

}
