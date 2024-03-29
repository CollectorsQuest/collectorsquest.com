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

  public function executeAmericanPickers(sfWebRequest $request)
  {
    $american_pickers = sfConfig::get('app_aetn_american_pickers');

    $collection = CollectorCollectionQuery::create()->findOneById($american_pickers['collection']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    /**
     * Increment the number of views
     */
    $this->incrementCounter($collection, 'NumViews');

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollectionId($american_pickers['collection'])
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 9);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarAmericanPickers');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection, array('route' => 'aetn_american_pickers'));

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_american_pickers'));

    return sfView::SUCCESS;
  }

  public function executeAmericanRestoration(sfWebRequest $request)
  {
    /** @var $aetn_shows array */
    $aetn_shows = sfConfig::get('app_aetn_shows');

    $collection = CollectorCollectionQuery::create()
      ->findOneById($aetn_shows['american_restoration']['collection']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    /**
     * Increment the number of views
     */
    $this->incrementCounter($collection, 'NumViews');

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollection($collection)
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 12);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    $this->collection = $collection;

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarAmericanRestoration');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection, array('route' => 'aetn_american_restoration'));

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_american_restoration'));

    return sfView::SUCCESS;
  }

  public function executePawnStars()
  {
    return $this->redirect('@homepage', 301);
  }

  public function executePickedOff(sfWebRequest $request)
  {
    $picked_off = sfConfig::get('app_aetn_picked_off');

    $collection = CollectorCollectionQuery::create()->findOneById($picked_off['collection']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    /**
     * Increment the number of views
     */
    $this->incrementCounter($collection, 'NumViews');

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollectionId($picked_off['collection'])
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 12);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    $this->collection = $collection;

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarPickedOff');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection, array('route' => 'aetn_picked_off'));

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_picked_off'));

    return sfView::SUCCESS;
  }

  public function executeCountingCars(sfWebRequest $request)
  {
    $couting_cars = sfConfig::get('app_aetn_counting_cars');

    $collection = CollectorCollectionQuery::create()->findOneById($couting_cars['collection']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    /**
     * Increment the number of views
     */
    $this->incrementCounter($collection, 'NumViews');

    $q = FrontendCollectionCollectibleQuery::create()
        ->filterByCollectionId($couting_cars['collection'])
        ->orderByPosition(Criteria::ASC)
        ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 12);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    $this->collection = $collection;

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarCountingCars');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection, array('route' => 'aetn_counting_cars'));

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_counting_cars'));

    return sfView::SUCCESS;
  }

  public function executeFranksPicks(sfWebRequest $request)
  {
    /* @var $aetn_shows array */
    $aetn_shows = sfConfig::get('app_aetn_shows', array());
    $collection = CollectorCollectionQuery::create()->findOneById($aetn_shows['american_pickers']['franks_picks']);
    $this->forward404Unless($collection instanceof CollectorCollection);

    $this->collection = $collection;

    /**
     * Increment the number of views
     */
    $this->incrementCounter($collection, 'NumViews');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection, array('route' => 'aetn_franks_picks'));

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_franks_picks'));

    return sfView::SUCCESS;
  }

  public function executeMwba()
  {
    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_mwba'));

    return sfView::SUCCESS;
  }

  public function executeMwbaPetroliana()
  {
    $collectible_ids = array(
        461, 86332, 88537, 82253,
      78137, 76082, 28180, 84250,
      88871, 76081, 88883, 88888,
      28179,  9618, 92017,  8289,
      56797, 84361, 84447
    );

    /**
     * Get the Collectibles
     *
     * @var $q FrontendCollectibleQuery
     */
    $q = FrontendCollectibleQuery::create()
      ->filterById($collectible_ids, Criteria::IN)
      ->addAscendingOrderByColumn('FIELD(id, '. implode(',', $collectible_ids) .')');

    $this->collectibles = $q->find();

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_mwba_petroliana'));

    return sfView::SUCCESS;
  }

  public function executeMwbaRooseveltiana()
  {
    $collectible_ids = array(
      16967, 56670, 56218, 16610,
      16604, 16608, 87811, 16601,
      75360, 87820, 87819, 87818,
      16612, 16615, 87812, 87807,
      87817, 16611, 87814, 87816,
      16503, 87809, 87808, 87813,
      87805, 87810
    );

    /**
     * Get the Collectibles
     *
     * @var $q FrontendCollectibleQuery
     */
    $q = FrontendCollectibleQuery::create()
      ->filterById($collectible_ids, Criteria::IN)
      ->addAscendingOrderByColumn('FIELD(id, '. implode(',', $collectible_ids) .')');

    $this->collectibles = $q->find();

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_mwba_rooseveltiana'));

    return sfView::SUCCESS;
  }

  public function executeMwbaRailroadiana()
  {
    $collectible_ids = array(
      87910, 89843, 89893,  5420,
      89864, 56685, 12738, 93008,
      87902, 89817, 89878,  5304,
      81752, 84281, 87252, 83667,
      85889, 89802, 89881, 87888,
      89867, 89765, 89757, 92019,
      89822, 87912, 89530, 88573,
      87887, 89806
    );

    /**
     * Get the Collectibles
     *
     * @var $q FrontendCollectibleQuery
     */
    $q = FrontendCollectibleQuery::create()
      ->filterById($collectible_ids, Criteria::IN)
      ->addAscendingOrderByColumn('FIELD(id, '. implode(',', $collectible_ids) .')');

    $this->collectibles = $q->find();

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('aetn_mwba_railroadiana'));

    return sfView::SUCCESS;
  }

}
