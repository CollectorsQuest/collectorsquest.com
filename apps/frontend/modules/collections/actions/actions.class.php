<?php

class collectionsActions extends cqFrontendActions
{

  public function executeIndex()
  {
    $this->collections = CollectionQuery::create()->limit(12)->find();

    return sfView::SUCCESS;
  }

  public function executeCategories()
  {
    return sfView::SUCCESS;
  }

  public function executeCategory()
  {
    $this->category = $this->getRoute()->getObject();

    return sfView::SUCCESS;
  }

}
