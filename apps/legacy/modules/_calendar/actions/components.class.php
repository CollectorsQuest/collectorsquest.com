<?php

class _calendarComponents extends sfComponents
{
  public function executeSidebar()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'This Week Events',
        'icon' => 'calendar',
        'route' => '/calendar/events/index.php',
        'active' => !$this->getRequestParameter('com')
      ),
      1 => array(
        'text' => 'Submit an Event',
        'icon' => 'plusthick',
        'route' => '/calendar/events/index.php?com=submit',
        'active' => ($this->getRequestParameter('com') == 'submit')
      ),
      2 => array(
        'text' => 'Search for Events',
        'icon' => 'search',
        'route' => '/calendar/events/index.php?com=search',
        'active' => ($this->getRequestParameter('com') == 'search')
      ),
      3 => array(
        'text' => 'Hot List',
        'icon' => 'note',
        'route' => '/calendar/events/index.php?com=hotlist',
        'active' => ($this->getRequestParameter('com') == 'hotlist')
      )
    );

    return sfView::SUCCESS;
  }
}