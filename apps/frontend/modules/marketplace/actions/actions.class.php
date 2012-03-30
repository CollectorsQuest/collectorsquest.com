<?php

class marketplaceActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $this->collectibles = CollectibleQuery::create()->limit(12)->find();

    return sfView::SUCCESS;
  }
}
