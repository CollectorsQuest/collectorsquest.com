<?php

class searchActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $q = CollectibleQuery::create()
       ->filterByCollectorId(9782);
    $this->collectibles = $q->limit(10)->find();

    return sfView::SUCCESS;
  }
}
