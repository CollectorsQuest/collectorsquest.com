<?php

require_once __DIR__ .'/../../../lib/collectorsquest/cqApplicationConfiguration.class.php';

class frontendConfiguration extends cqApplicationConfiguration
{
  public function setup()
  {
    parent::setup();

    $this->enablePlugins(array('sfFeed2Plugin'));

    $this->dispatcher->connect('user.change_authentication', array('CollectorPeer', 'listenToChangeAuthenticationEvent'));
  }
}
