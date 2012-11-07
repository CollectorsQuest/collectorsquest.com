<?php

class sellerComponents extends cqFrontendComponents
{
  public function executeSidebarSignup()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarStore(sfWebRequest $request)
  {
    /* @var $collector Collector */
    $collector = $request->getAttribute('sf_route')->getObject();

    $this->store_shipping = $collector->getSellerSettingsShipping();
    $this->store_refunds = $collector->getSellerSettingsRefunds();
    $this->store_return_policy = $collector->getSellerSettingsReturnPolicy();
    $this->store_additional_policies = $collector->getSellerSettingsAdditionalPolicies();

    /* @var $q FrontendCollectorCollectionQuery */
    $q = FrontendCollectorCollectionQuery::create()
      ->filterByCollector($collector)
      ->hasCollectiblesForSale();

    $this->collections = $q->find();
    $this->collection_id = $this->getRequestParameter('collection_id');
    $this->collector = $collector;
  }
}
