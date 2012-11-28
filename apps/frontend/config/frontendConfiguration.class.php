<?php

require_once __DIR__ .'/../../../lib/config/cqApplicationConfiguration.class.php';

class frontendConfiguration extends cqApplicationConfiguration
{
  /** @var IcePatternRouting */
  protected $backendRouting = null;

  public function setup()
  {
    parent::setup();

    $this->enablePlugins(array('sfFeed2Plugin'));

    $this->dispatcher->connect(
      'user.change_authentication', array('CollectorPeer', 'listenToChangeAuthenticationEvent')
    );
  }

  public function generateBackendUrl($name, $parameters = array())
  {
    $url = $this->getBackendRouting()->generate($name, $parameters, true);
    return 'http://www.'. sfConfig::get('app_domain_name', 'collectorsquest.com') .'/backend.php' . $url;
  }

  /**
   * @return IcePatternRouting
   */
  public function getBackendRouting()
  {
    if (!$this->backendRouting)
    {
      $this->backendRouting = new sfPatternRouting(new sfEventDispatcher());

      $config = new sfRoutingConfigHandler();
      $routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/backend/config/routing.yml',
        sfConfig::get('sf_plugins_dir').'/iceBackendPlugin/config/routing.yml'));

      $this->backendRouting->setRoutes($routes);
    }

    return $this->backendRouting;
  }

}
