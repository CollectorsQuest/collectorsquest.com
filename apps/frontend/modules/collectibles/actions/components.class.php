<?php

class collectiblesComponents extends cqFrontendComponents
{
  /**
   * Should only be used with these routes:
   *
   * @uri     /collectibles/by/:id/:slug
   * @uri     /collectibles-for-sale/by/:id/:slug
   */
  public function executeSidebarCollectorList(sfWebRequest $request)
  {
    $this->collector = $request->getAttribute('sf_route')->getObject();

    $this->store_shipping = $this->collector->getSellerSettingsShipping();
    $this->store_refunds = $this->collector->getSellerSettingsRefunds();
    $this->store_return_policy = $this->collector->getSellerSettingsReturnPolicy();
    $this->store_additional_policies = $this->collector->getSellerSettingsAdditionalPolicies();

    /* @var $q FrontendCollectorCollectionQuery */
    $q = FrontendCollectorCollectionQuery::create()
      ->filterByCollector($this->collector)
      ->hasCollectiblesForSale();

    $this->collections = $q->find();

    $this->collection_id = $this->getVar('collection_id') ?: null;
  }

}
