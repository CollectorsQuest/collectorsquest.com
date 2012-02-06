<?php

class backendConfiguration extends sfApplicationConfiguration
{
  protected $frontendRouting = null;

  public function configure()
  {
    ;
  }

  public function generateFrontendUrl($name, $parameters = array())
  {
    return 'http://'. sfConfig::get('app_www_domain') . $this->getFrontendRouting()->generate($name, $parameters, true);
  }

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
}
