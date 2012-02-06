<?php

class generalComponents extends sfComponents
{
  public function executeIndexTheme()
  {
    $themes = array(
      0 => array(
        'collections' => array(57, 869, 1615, 263),
        'collectibles' => array(6662, 7714, 39980, 39414, 37797, 37711, 37045, 37007, 39043, 35784, 13481, 34599, 36088, 9770, 21974, 35932, 35871, 34582, 13483, 2761)
      ),
      1 => array(
        'collections' => array(1615, 263),
        'collectibles' => array(13483, 28853, 234, 23004, 21826, 7116, 1270, 245, 63, 1302, 11273, 6937, 32274, 7874, 7882, 12959, 24247, 24243, 19941, 35871)
      ),
      2 => array(
        'collections' => array(256, 180, 2228, 1535),
        'collectibles' => array(14777, 15913, 7951, 15851, 19767, 3515, 10876, 19961, 19941, 24568, 10754, 26030, 7800, 31060, 7731, 5957, 575, 397, 32432, 24243)
      ),
      3 => array(
        'collections' => array(1335, 1866, 256, 180),
        'collectibles' => array(13870, 241, 2761, 18650, 17582, 30195, 33222, 7258, 16591, 18115, 20012, 19596, 6170, 7725, 6684, 6433, 37960, 19961, 21986, 14777)
      ),
      4 => array(
        'collections' => array(2228, 1615, 1535, 1866),
        'collectibles' => array(8947, 37960, 21986, 689, 28975, 4483, 228, 35844, 669, 9526, 961, 22584, 19797, 13762, 25919, 8003, 34093, 6662, 19596, 397)
      ),
      5 => array(
        'collections' => array(1335, 1866, 256, 180),
        'collectibles' => array(13870, 16503, 2761, 18650, 17582, 52813, 33222, 12836, 16591, 15797, 20012, 19596, 15614, 7725, 36385, 6433, 37960, 19961, 8009, 14777)
      )
    );

    $i = array_rand($themes);

    $c = new Criteria();
    $c->setDistinct();
    $c->addJoin(CollectionPeer::ID, MultimediaPeer::MODEL_ID);
    $c->add(CollectionPeer::NUM_ITEMS, 4, Criteria::GREATER_EQUAL);
    $c->add(MultimediaPeer::MODEL, 'Collection');
    $c->add(CollectionPeer::ID, $themes[$i]['collections'], Criteria::IN);
    $c->setLimit(2);

    $this->collections = CollectionPeer::doSelect($c);

    $c = new Criteria();
    $c->setDistinct();
    $c->add(CollectiblePeer::ID, $themes[$i]['collectibles'], Criteria::IN);
    $c->addJoin(CollectiblePeer::COLLECTION_ID, CollectionPeer::ID);
    $c->addJoin(CollectiblePeer::ID, MultimediaPeer::MODEL_ID);
    $c->add(MultimediaPeer::MODEL, 'Collectible');
    $c->addDescendingOrderByColumn(CollectiblePeer::SCORE);
    $c->setLimit(18);

    $this->collectibles = CollectiblePeer::doSelect($c);

    $this->theme = 1;
    $this->colors = array('#DF912F', '#BADC70', '#9CB7D7', 'transparent');

    return sfView::SUCCESS;
  }

  public function executeBreadcrumbs()
  {
    $breadcrumbs = cqBreadcrumbs::getInstance();

    if (isset($this->root))
    {
      $breadcrumbs->setRoot($this->root['text'], $this->root['uri']);
    }

    if (!isset($this->offset))
    {
      $this->offset = 0;
    }

    $this->items = $breadcrumbs->getItems($this->offset);

    return sfView::SUCCESS;
  }

  public function executeRightAds()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebar()
  {
    return sfView::SUCCESS;
  }

  public function executeIndexSlot1()
  {
    return sfView::SUCCESS;
  }
}
