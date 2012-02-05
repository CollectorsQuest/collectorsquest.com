<?php

class communityComponents extends sfComponents
{
  public function executeSidebar()
  {
    $this->featured_weeks = FeaturedPeer::getPastFeatured(FeaturedPeer::TYPE_FEATURED_WEEK, 3);

    return sfView::SUCCESS;
  }

  public function executeSidebarSpotlight()
  {
    $this->featured_weeks = FeaturedPeer::getPastFeatured(FeaturedPeer::TYPE_FEATURED_WEEK, 5);

    return sfView::SUCCESS;
  }
}
