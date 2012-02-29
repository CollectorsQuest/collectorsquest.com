<?php

class shoppingComponents extends sfComponents
{
  public function executeSidebar()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'Keep Shopping',
        'icon' => 'note',
        'route' => '@marketplace'
      )
    );

    return sfView::SUCCESS;
  }
}
