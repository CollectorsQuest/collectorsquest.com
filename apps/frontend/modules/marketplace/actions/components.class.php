<?php

class marketplaceComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    /** @var $q CollectionCategoryQuery */
    $q = CollectionCategoryQuery::create()
      ->distinct()
      ->filterByName('None', Criteria::NOT_EQUAL)
      ->orderBy('Name', Criteria::ASC)
      ->joinCollection()
      ->useCollectionQuery()
        ->joinCollectionCollectible()
        ->useCollectionCollectibleQuery()
          ->joinCollectible()
            ->useCollectibleQuery()
              ->joinCollectibleForSale()
              ->useCollectibleForSaleQuery()
                ->filterByIsSold(false)
              ->endUse()
            ->endUse()
          ->endUse()
        ->endUse();
    $categories = $q->find();

    $this->categories = IceFunctions::array_vertical_sort($categories, 2);

    return sfView::SUCCESS;
  }
}
