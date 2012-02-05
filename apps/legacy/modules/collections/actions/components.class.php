<?php

class collectionsComponents extends sfComponents
{
  public function executeSidebar()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'Most Recent',
        'icon' => 'refresh',
        'route' => '@collections_by_filter?filter=most-recent',
        'active' => ($this->getRequestParameter('filter') == 'most-recent')
      ),
      1 => array(
        'text' => 'Most Popular',
        'icon' => 'star',
        'route' => '@collections_by_filter?filter=most-popular',
        'active' => ($this->getRequestParameter('filter') == 'most-popular')
      ),
      2 => array(
        'text' => 'Most Talked-About',
        'icon' => 'note',
        'route' => '@collections_by_filter?filter=most-talked-about',
        'active' => ($this->getRequestParameter('filter') == 'most-talked-about')
      )
    );

    $this->tags = CollectionPeer::getPopularTags(90);
    uksort($this->tags, "strcasecmp");

    return sfView::SUCCESS;
  }
}
