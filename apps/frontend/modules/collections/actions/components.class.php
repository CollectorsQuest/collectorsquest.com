<?php

class collectionsComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebarCategory()
  {
    return sfView::SUCCESS;
  }

  public function executeFeaturedWeek()
  {
    $q = CollectibleQuery::create()->limit(4);
    $this->collectibles = $q->find();

    return sfView::SUCCESS;
  }

  public function executeFeaturedWeekCollectibles()
  {
    $q = CollectibleQuery::create()->limit(12);
    $this->collectibles = $q->find();

    return sfView::SUCCESS;
  }
}
