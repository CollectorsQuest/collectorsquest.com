<?php

class aentComponents extends sfComponents
{
  public function executeCollectible()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getVar('collectible');

    /** @var $collection Collection */
    $collection = $collectible->getCollection();

    /** @var $collector Collector */
    $collector = $collectible->getCollector();

    $pawn_stars = sfConfig::get('app_aent_pawn_stars');
    $american_pickers = sfConfig::get('app_aent_american_pickers');

    $q = CollectibleQuery::create()
       ->filterByCollectorId($pawn_stars['collector'])
       ->filterByCollectionId($pawn_stars['collection'])
       ->orderById(Criteria::ASC);
    $ps_collectibles = $q->find();

    $q = CollectibleQuery::create()
       ->filterByCollectorId($american_pickers['collector'])
       ->filterByCollectionId($american_pickers['collection'])
       ->orderById(Criteria::ASC);
    $ap_collectibles = $q->find();

    /**
     * Figure out the previous and the next item in the collection
     */
    $collectible_ids = $collection->getCollectibleIds();

    if (array_search($collectible->getId(), $collectible_ids) - 1 < 0)
    {
      $this->previous = CollectiblePeer::retrieveByPk(
        $collectible_ids[count($collectible_ids) - 1]
      );
    }
    else
    {
      $this->previous = CollectiblePeer::retrieveByPk(
        $collectible_ids[array_search($collectible->getId(), $collectible_ids) - 1]
      );
    }

    if (array_search($collectible->getId(), $collectible_ids) + 1 >= count($collectible_ids))
    {
      $this->next = CollectiblePeer::retrieveByPk($collectible_ids[0]);
    }
    else
    {
      $this->next = CollectiblePeer::retrieveByPk(
        $collectible_ids[array_search($collectible->getId(), $collectible_ids) + 1]
      );
    }

    /**
     * Pawn Stars
     */
    if ($collectible->getId() == $ps_collectibles[0]->getId())
    {
      $collectible_ids = array(56079, 56082, 56103, 56066, 56371, 56213);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1288, 1044, 478, 401, 1280, 1043, 2792, 1042), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[1]->getId())
    {
      $collectible_ids = array(56541, 56388, 56186, 56075, 56072, 56074);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1597, 1244, 1585, 1249, 1268, 2815, 602, 1168), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[2]->getId())
    {
      $collectible_ids = array(56684, 56214, 56630, 56077, 56684, 56632, 56684, 56683);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1811, 1953, 1122, 375, 378, 30928, 374, 1161, 1727), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[3]->getId())
    {
      $collectible_ids = array(28379, 56403, 56262, 56195, 23662, 56263);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(2791, 2173, 1281, 1452, 2373, 1290, 699, 1183), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[4]->getId())
    {
      $collectible_ids = array(56078, 56081, 56528, 56514, 56530, 56080);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(2151, 270, 2649, 1695, 2651, 2831, 1249, 675, 2151), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[5]->getId())
    {
      $collectible_ids = array(55530, 56579, 33493, 56573, 15630, 56577);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1080, 866, 855, 862, 863, 949, 463, 970), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[6]->getId())
    {
      $collectible_ids = array(56210, 22181, 56509, 56065, 56063, 56029, 56761);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1484, 1352, 1287, 1280, 1260, 1346, 1337, 1335), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[7]->getId())
    {
      $collectible_ids = array(56599, 2308, 56600, 56545, 56189, 56676, 56661);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1117, 290, 1138, 1323, 1072, 1151, 2180, 2308), Criteria::IN
      )->orderById('DESC')->find();
    }
    else if ($collectible->getId() == $ps_collectibles[8]->getId())
    {
      $collectible_ids = array(56600, 56597, 56543, 56088, 56396, 56393);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1847, 545, 1290, 789, 477, 307, 1934, 823), Criteria::IN
      )->orderById('DESC')->find();
    }

    /**
     * American Pickers
     */
    else if ($collectible->getId() == $ap_collectibles[0]->getId())
    {
      $collectible_ids = array(56402, 56180, 56206, 56090, 56398, 56091);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(531, 891, 807, 982, 1123, 557, 1547, 838), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[1]->getId())
    {
      $collectible_ids = array(56663, 56680, 56599, 18210, 56094, 23304, 56859);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(761, 1284, 228, 495, 156, 11, 914, 294), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[2]->getId())
    {
      $collectible_ids = array(56540, 56759, 56761, 22184, 56063, 56760);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1780, 1335, 1457, 2838, 831, 1337, 1303, 1485), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[3]->getId())
    {
      $collectible_ids = array(56544, 56590, 56596, 56593, 56591, 56598);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(1329, 1328, 852, 1573, 1048, 2713, 512, 2201), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[4]->getId())
    {
      $collectible_ids = array(56175, 56028, 56534, 56030, 56616, 56618);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(260, 234, 1168, 263, 51, 843, 2810, 1098), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[5]->getId())
    {
      $collectible_ids = array(56395, 56622, 11132, 56035, 56619, 56034);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(390, 932, 465, 201, 447, 804, 708, 2117), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[6]->getId())
    {
      $collectible_ids = array(56762, 56382, 56757, 23705, 56381, 51400);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(619, 2844, 40, 122, 454, 894, 456, 703), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[7]->getId())
    {
      $collectible_ids = array(56784, 23054, 56400, 56753, 20207, 56393);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(752, 616, 807, 1576, 437, 812, 1584, 1183), Criteria::IN
      )->find();
    }
    else if ($collectible->getId() == $ap_collectibles[8]->getId())
    {
      $collectible_ids = array(56753, 56681, 56380, 51391, 56664, 56662);
      $q = CollectibleForSaleQuery::create()
         ->joinWith('Collectible')->useQuery('Collectible')->endUse()
         ->filterByCollectibleId($collectible_ids, Criteria::IN)
         ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
      $this->collectibles_for_sale = $q->find();

      $this->featured = CollectionQuery::create()->filterById(
        array(819, 618, 26, 574, 752, 1727, 1059, 910), Criteria::IN
      )->find();
    }
    /**
     * Fall back
     */
    else
    {
      $this->collectibles_for_sale = $collectible->getRelatedCollectiblesForSale(6);
      $this->featured = CollectionPeer::getRelatedCollections($collectible, 8);
    }

    $this->collectible = $collectible;
    $this->collection = $collection;

    if ($collection->getId() == $american_pickers['collection'])
    {
      $this->title = "Picked from America's Backyards";
    }
    else if ($collection->getId() == $pawn_stars['collection'])
    {
      $this->title = "Rich History for the Right Price";
    }
    else
    {
      $this->title = null;
    }

    if ($videos = $collectible->getMultimedia(false, 'video'))
    {
      $this->video = $videos[0];
    }
  }
}
