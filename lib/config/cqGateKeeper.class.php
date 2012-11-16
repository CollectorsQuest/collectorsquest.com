<?php

class cqGateKeeper
{
  public static function open($name, $type = 'feature')
  {
    $config = self::flattenConfigurationWithEnvironment();

    // Make sure the type is plural
    $type = rtrim($type, 's'). 's';

    if (isset($config[$type]))
    {
      $name = strtolower(sfInflector::underscore(str_replace(' ', '', $name)));

      return isset($config[$type][$name]) ? (boolean) $config[$type][$name] : false;
    }

    return false;
  }

  public static function locked($name, $type = 'feature')
  {
    return !self::open($name, $type);
  }

  /**
   * Merges default, all and current environment configurations.
   *
   * @return array The merged configuration
   */
  static public function flattenConfigurationWithEnvironment()
  {
    $cq_context = cqContext::getInstance();
    $configCache = $cq_context->getConfiguration()->getConfigCache();

    // load base gatekeeper
    include($configCache->checkConfig('config/gatekeeper.yml'));
    if ($file = $configCache->checkConfig('config/gatekeeper.yml', true))
    {
      $config = include($file);
    }

    $env = cqConfig::get('sf_environment');

    // Admin users get the settings from the "next" environment
    if ($env === 'prod' && $cq_context->getUser()->isAdmin())
    {
      $env = 'next';
    }

    return sfToolkit::arrayDeepMerge(
      isset($config['default']) && is_array($config['default']) ? $config['default'] : array(),
      isset($config['all']) && is_array($config['all']) ? $config['all'] : array(),
      isset($config[$env]) && is_array($config[$env]) ? $config[$env] : array()
    );
  }
}
