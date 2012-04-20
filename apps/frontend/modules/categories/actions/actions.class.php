<?php

class categoriesActions extends cqFrontendActions
{
  public function executeIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeCategory()
  {
    $this->category = $this->getRoute()->getObject();

    $q = CollectionQuery::create()
       ->filterByCollectionCategory($this->category)
       ->limit(16);

    $this->collections = $q->find();

    return sfView::SUCCESS;
  }
}
