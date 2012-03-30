<?php

class marketplaceComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    $q = CollectionCategoryQuery::create()
       ->filterById(0, Criteria::NOT_EQUAL)
       ->filterByParentId(0, Criteria::EQUAL)
       ->orderByName(Criteria::ASC)
       ->limit(30);
    $categories = $q->find();

    $this->categories = IceFunctions::array_vertical_sort($categories, 2);

    return sfView::SUCCESS;
  }
}
