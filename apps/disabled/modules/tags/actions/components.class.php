<?php

class tagsComponents extends sfComponents
{
  public function executeSidebar()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'Collections',
        'icon' => 'tag',
        'route' => '@tags?which=collections',
        'active' => 'collections' == $this->getRequestParameter('which')
      ),
      1 => array(
        'text' => 'Collectibles',
        'icon' => 'tag',
        'route' => '@tags?which=collectibles',
        'active' => ($this->getRequestParameter('com') == 'submit')
      ),
      2 => array(
        'text' => 'Countries',
        'icon' => 'tag',
        'route' => '@tags?which=countries',
        'active' => ($this->getRequestParameter('com') == 'hotlist')
      )
    );

    return sfView::SUCCESS;
  }
}
