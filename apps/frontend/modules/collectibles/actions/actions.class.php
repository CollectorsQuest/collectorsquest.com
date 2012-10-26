<?php

/**
 * collectibles actions.
 */
class collectiblesActions extends cqFrontendActions
{

 /**
  * List collectibles for a particular collector
  *
  * @url  /collectibles/by/:id/:slug
  * @url  /collectibles-for-sale/by/:id/:slug
  */
  public function executeCollectorList(sfWebRequest $request)
  {
    $collector = $this->getRoute()->getObject();

    if ($request->getParameter('legacy'))
    {
      $this->redirect('collector_by_slug', $collector, 301);
    }

    $for_sale_ids = FrontendCollectibleForSaleQuery::create()
      ->filterByCollector($collector)
      ->isForSale()
      ->select('CollectibleId')
      ->find()->getArrayCopy();

    $q = FrontendCollectionCollectibleQuery::create()
      ->groupBy('CollectionCollectible.CollectibleId')
      ->joinWith('CollectionCollectible.Collectible')
      ->joinWith('Collectible.CollectibleForSale')
      ->filterByCollector($collector)
      ->_if($request->getParameter('onlyForSale'))
        ->filterByCollectibleId($for_sale_ids, Criteria::IN)
      ->_else()
        ->filterByCollectibleId($for_sale_ids, Criteria::NOT_IN)
      ->_endif();

    $pager = new PropelModelPager($q, 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    if ($request->getParameter('onlyForSale'))
    {
      SmartMenu::setSelected('collectibles_for_collector_list', 'for_sale');
      $this->title = 'Items for sale by '. $collector;
      $this->addBreadcrumb('Items for Sale');

      // Set Canonical Url meta tag
      $this->getResponse()->setCanonicalUrl($this->generateUrl('collector_shop', $collector));
    }
    else
    {
      SmartMenu::setSelected('collectibles_for_collector_list', 'normal');
      $this->title = 'Collectibles by '. $collector;
      $this->addBreadcrumb('Collectibles');

      // Set Canonical Url meta tag
      /* routing no longer in use!
       $this->getResponse()->setCanonicalUrl($this->generateUrl('collectibles_by_collector', $collector));
      */
    }

    $this->collector = $collector;
    $this->pager = $pager;
  }

}
