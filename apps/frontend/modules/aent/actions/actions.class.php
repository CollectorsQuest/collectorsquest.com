<?php

class aentActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header_main_menu', 'collections');
  }

  public function executeIndex()
  {
    $this->redirect('@aetn_landing', 302);
  }

  public function executeLanding()
  {
    $category = ContentCategoryQuery::create()->findOneBySlug('history-militaria'); //History-and-militaria

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

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    return sfView::SUCCESS;
  }

  public function executePickedOff(sfWebRequest $request)
  {
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

    /** @var $q CollectibleForSaleQuery */
    $q = CollectibleForSaleQuery::create()
      ->filterByCollection($collection)
      ->limit(8);
    $this->collectibles_for_sale = array(); //$q->find();

    $this->collection = $collection;

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collection);

    return sfView::SUCCESS;
  }

  public function executeStorageWars()
  {
    return sfView::SUCCESS;
  }

  public function executeCollectible()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();

    /** @var $collection Collection */
    $collection = $collectible->getCollection();

    $american_pickers = sfConfig::get('app_aetn_american_pickers');
    $pawn_stars       = sfConfig::get('app_aetn_pawn_stars');
    $picked_off       = sfConfig::get('app_aetn_picked_off');
    $storage_wars     = sfConfig::get('app_aetn_storage_wars');

    if ($collection->getId() === $american_pickers['collection'])
    {
      $this->brand = 'American Pickers';
    }
    else if ($collection->getId() === $pawn_stars['collection'])
    {
      $this->brand = 'Pawn Stars';
    }
    else if ($collection->getId() === $picked_off['collection'])
    {
      $this->brand = 'Picked Off';
    }
    else if ($collection->getId() === $storage_wars['collection'])
    {
      $this->brand = 'Storage Wars';
    }

    /**
     * Increment the number of views
     */
    if (!$this->getCollector()->isOwnerOf($collectible))
    {
      $collectible->setNumViews($collection->getNumViews() + 1);
      $collectible->save();
    }

    $tags = $collectible->getTags();
    $q = CollectibleQuery::create()
       ->filterById($collectible->getId(), Criteria::NOT_EQUAL)
       ->filterByTags($tags)
       ->orderByNumViews(Criteria::DESC)
       ->limit(8);
    $this->related_collectibles = $q->find();

    $this->collectible = $collectible;
    $this->collection  = $collection;

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collectible);

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl(
      'http://' . sfConfig::get('app_www_domain') .
      $this->generateUrl('aetn_collectible_by_slug', array('sf_subject' => $collectible), false)
    );

    return sfView::SUCCESS;
  }

  public function executeCollectibleFixedMatching()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();

    /** @var $collection Collection */
    $collection = $collectible->getCollectorCollection();

    $pawn_stars = sfConfig::get('app_aetn_pawn_stars');
    $american_pickers = sfConfig::get('app_aetn_american_pickers');

    /** @var $q CollectibleQuery */
    $q = CollectionCollectibleQuery::create()
      ->filterByCollectionId($pawn_stars['collection'])
      ->orderByCollectibleId(Criteria::ASC);

    /** @var $ps_collectibles CollectionCollectible[] */
    $ps_collectibles = $q->find();

    $q = CollectionCollectibleQuery::create()
      ->filterByCollectionId($american_pickers['collection'])
      ->orderByCollectibleId(Criteria::ASC);

    /** @var $ap_collectibles CollectionCollectible[] */
    $ap_collectibles = $q->find();

    /**
     * Pawn Stars
     */
    if ($collectible->getId() == $ps_collectibles[0]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1288, 1044, 478, 401, 1280, 1043, 2792, 1042), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[1]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1597, 1244, 1585, 1249, 1268, 2815, 602, 1168), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[2]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1811, 1953, 1122, 375, 378, 30928, 374, 1161, 1727), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[3]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(2791, 2173, 1281, 1452, 2373, 1290, 699, 1183), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[4]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(2151, 270, 2649, 1695, 2651, 2831, 1249, 675, 1044, 1782), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[5]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1080, 866, 855, 862, 863, 949, 463, 970), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[6]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1484, 1352, 1287, 1280, 1260, 1346, 1337, 1335), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[7]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1117, 290, 1138, 1323, 1072, 1151, 2180, 2308), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[8]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1847, 545, 1290, 789, 477, 307, 1934, 823), Criteria::IN
      )->orderById('DESC')->find();
    }

    /**
     * American Pickers
     */
    else if ($collectible->getId() == $ap_collectibles[0]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(531, 891, 807, 982, 1123, 557, 1547, 838, 2888), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[1]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(761, 1284, 228, 495, 156, 11, 914, 294), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[2]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1780, 1335, 1457, 2838, 831, 1337, 1303, 1485), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[3]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(1329, 1328, 852, 1573, 1048, 2713, 512, 2201), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[4]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(260, 234, 1168, 263, 51, 843, 2810, 1098, 415), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[5]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(390, 932, 465, 201, 447, 804, 708, 2117), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[6]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(619, 2844, 40, 122, 454, 894, 456, 703), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[7]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(752, 616, 807, 1576, 437, 812, 1584, 1183, 709), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[8]->getId())
    {
      $this->related_collections = CollectorCollectionQuery::create()->filterById(
        array(819, 618, 26, 574, 752, 1727, 1059, 910), Criteria::IN
      )->find();
    }
    /**
     * Fall back
     */
    else
    {
      $this->related_collections = CollectorCollectionPeer::getRelatedCollections($collectible, 8);
    }

    $this->collectible = $collectible;
    $this->collection = $collection;

    if ($collection->getId() == $american_pickers['collection'])
    {
      $this->brand = 'American Pickers';
    }
    else if ($collection->getId() == $pawn_stars['collection'])
    {
      $this->brand = 'Pawn Stars';
    }
    else
    {
      $this->brand = null;
    }

    if ($videos = $collectible->getMultimedia(1, 'video', false))
    {
      $this->video = $videos;
    }

    // Set the OpenGraph meta tags
    $this->getResponse()->addOpenGraphMetaFor($collectible);

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl(
      'http://' . sfConfig::get('app_www_domain') .
      $this->generateUrl('aetn_collectible_by_slug_fixed_matching', array('sf_subject' => $collectible), false)
    );

    $this->setTemplate('collectible');

    return sfView::SUCCESS;
  }
}
