<?php

class collectorsComponents extends sfComponents
{
  public function executeSidebar()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'Most Popular',
        'icon' => 'star',
        'route' => '@collectors_by_filter?filter=most-popular',
        'active' => ($this->getRequestParameter('filter') == 'most-popular')
      ),
     /**
      1 => array(
        'text' => 'Online Now',
        'icon' => 'clock',
        'route' => '@collectors_by_filter?filter=online-now',
        'active' => ($this->getRequestParameter('filter') == 'online-now')
      )
     */
    );

    $collector_profile = $this->getUser()->getCollector()->getProfile();
    if ($collector_profile && in_array($collector_profile->getCountry(), array('United States', 'USA', 'US')))
    {
      $this->buttons[] = array(
        'text' => 'Near You',
        'icon' => 'home',
        'route' => '@collectors_by_filter?filter=near-you',
        'active' => ($this->getRequestParameter('filter') == 'near-you')
      );
    }

    $this->collections = array();

    return sfView::SUCCESS;
  }
}
