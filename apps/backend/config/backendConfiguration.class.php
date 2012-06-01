<?php

require_once __DIR__ .'/../../../lib/collectorsquest/cqApplicationConfiguration.class.php';

class backendConfiguration extends cqApplicationConfiguration
{
  /** @var IcePatternRouting */
  protected $frontendRouting = null;

  /** @var IcePatternRouting */
  protected $legacyRouting = null;

  public function configure()
  {
    ;
  }

  public function generateFrontendUrl($name, $parameters = array())
  {
    return 'http://'. sfConfig::get('app_www_domain', 'www.collectorsquest.com') .
           $this->getFrontendRouting()->generate($name, $parameters, true);
  }

  /**
   * @return IcePatternRouting
   */
  public function getFrontendRouting()
  {
    if (!$this->frontendRouting)
    {
      $this->frontendRouting = new IcePatternRouting(new sfEventDispatcher());

      $config = new sfRoutingConfigHandler();
      $routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/frontend/config/routing.yml'));

      $this->frontendRouting->setRoutes($routes);
    }

    return $this->frontendRouting;
  }

  /**
   * @return IcePatternRouting
   */
  public function getLegacyRouting()
  {
    if (!$this->legacyRouting)
    {
      $this->legacyRouting = new IcePatternRouting(new sfEventDispatcher());

      $config = new sfRoutingConfigHandler();
      $routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/legacy/config/routing.yml'));

      $this->legacyRouting->setRoutes($routes);
    }

    return $this->legacyRouting;
  }
}
