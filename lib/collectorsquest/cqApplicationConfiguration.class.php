<?php

require dirname(__FILE__).'/../../plugins/iceLibsPlugin/lib/config/IceApplicationConfiguration.class.php';

class cqApplicationConfiguration extends IceApplicationConfiguration
{
  /**
   * Various initializations.
   */
  public function initConfiguration()
  {
    parent::initConfiguration();

    if ($file = $this->getConfigCache()->checkConfig('config/secure/app.yml', true))
    {
      include($file);
    }
  }
}
