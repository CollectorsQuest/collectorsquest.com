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

    $q = CollectionCollectibleQuery::create()
      ->filterByCollectionId($american_pickers['collection'])
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);
    $this->collectibles = $q->find();

    $collectible_ids = array(
      56402, 56180, 56206, 56090, 56398, 56091,
      56663, 56680, 56599, 56094, 23304, 56859,
      56540, 56759, 56761, 22184, 56063, 56760,
      56544, 56590, 56596, 56593, 56591, 56598,
      56175, 56028, 56534, 56030, 56616, 56618,
      56395, 56622, 11132, 56035, 56619, 56034,
      56762, 56382, 56757, 23705, 56381, 51400,
      56784, 23054, 56400, 56753, 20207, 56393,
      56753, 56681, 56380, 51391, 56664, 56662,
    );
    shuffle($collectible_ids);

    /** @var $q CollectibleForSaleQuery */
    $q = CollectibleForSaleQuery::create()
      ->filterByCollectibleId($collectible_ids, Criteria::IN)
      ->joinWith('Collectible')->useQuery('Collectible')->endUse()
      ->limit(8)
      ->addAscendingOrderByColumn('FIELD(collectible_id, ' . implode(',', $collectible_ids) . ')');
    $this->collectibles_for_sale = $q->find();

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarAmericanPickers');

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

    $q = CollectionCollectibleQuery::create()
      ->filterByCollectionId($pawn_stars['collection'])
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 9);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    $collectible_ids = array(
      56600, 56597, 56543, 56088, 56396, 56393,
      56599, 2308, 56600, 56545, 56189, 56676,
      56210, 22181, 56509, 56065, 56063, 56029,
      55530, 56579, 33493, 56573, 15630, 56577,
      56078, 56081, 56528, 56514, 56530, 56080,
      28379, 56403, 56262, 56195, 23662, 56263,
      56684, 56214, 56630, 56077, 56684, 56632,
      56541, 56388, 56186, 56075, 56072, 56074,
      56079, 56082, 56103, 56066, 56371, 56213,
      56684, 56683, 56761, 56661,
    );
    shuffle($collectible_ids);

    /** @var $q CollectibleForSaleQuery */
    $q = CollectibleForSaleQuery::create()
      ->filterByCollectibleId($collectible_ids, Criteria::IN)
      ->joinWith('Collectible')->useQuery('Collectible')->endUse()
      ->limit(8)
      ->addAscendingOrderByColumn('FIELD(collectible_id, ' . implode(',', $collectible_ids) . ')');
    $this->collectibles_for_sale = $q->find();

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

    $q = CollectionCollectibleQuery::create()
      ->filterByCollectionId($picked_off['collection'])
      ->orderByPosition(Criteria::ASC)
      ->orderByUpdatedAt(Criteria::ASC);

    $pager = new PropelModelPager($q, 9);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    $categories = ContentCategoryQuery::create()
      ->filterById(array(2, 364, 388, 674, 1559, 2409, 2836), Criteria::IN)
      ->find();

    /** @var $q CollectibleForSaleQuery */
    $q = CollectibleForSaleQuery::create()
      ->filterByContentCategoryWithDescendants($categories)
      ->isForSale()
      ->orderByUpdatedAt(Criteria::DESC);
    $this->collectibles_for_sale = $q->limit(8)->find();

    $this->collection = $collection;

    // Make the Collection available in the sidebar
    $this->setComponentVar('collection', $collection, 'sidebarPickedOff');

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    return sfView::SUCCESS;
  }

}
