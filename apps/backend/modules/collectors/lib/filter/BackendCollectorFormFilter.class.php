<?php

class BackendCollectorFormFilter extends CollectorFormFilter
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema['username'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::USERNAME
    ));

    $this->widgetSchema['display_name'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::DISPLAY_NAME
    ));
  }

}
