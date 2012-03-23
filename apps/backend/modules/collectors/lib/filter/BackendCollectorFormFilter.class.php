<?php

class BackendCollectorFormFilter extends CollectorFormFilter
{

  public function configure()
  {
    parent::configure();

    $this->setupUsernameField();
    $this->setupDisplayNameField();
  }

  public function setupUsernameField()
  {
    $this->widgetSchema['username'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::USERNAME
    ));
  }

  public function setupDisplayNameField()
  {
    $this->widgetSchema['display_name'] = new BackendWidgetFormModelTypeAhead(array(
      'field' => CollectorPeer::DISPLAY_NAME
    ));
  }

}
