<?php

class searchComponents extends cqFrontendComponents
{
  public function executeSidebar()
  {
    $q = CollectionCategoryQuery::create()
       ->filterById(0, Criteria::NOT_EQUAL)
       ->filterByParentId(0, Criteria::EQUAL)
       ->orderByName(Criteria::ASC)
       ->limit(30);
    $this->categories = $q->find();

    return sfView::SUCCESS;
  }
}
