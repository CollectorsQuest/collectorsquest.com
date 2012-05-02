<?php

/**
 * cqEmailConfig is the common configuration point for the plugin
 */
class cqEmailsConfig
{

  /** @var Twig_Loader_Filesystem */
  static $twig_loader;

  /** @var Twig_Environment */
  static $twig_environment;

  /** @var Twig_Environment */
  static $twig_string_environment;

  /**
   * @return    string
   */
  public static function getTemplatesDir()
  {
    return realpath(dirname(__FILE__).'/../email_templates/');
  }

  /**
   * Create the twig filesystem loader if it is not yet availabe and return it
   *
   * @return    Twig_Loader_Filesystem
   */
  public static function getTwigLoader()
  {
    if (null === self::$twig_loader)
    {
      self::$twig_loader =  new Twig_Loader_Filesystem(self::getTemplatesDir());
    }

    return self::$twig_loader;
  }

  /**
   * Create the twig environmetn if it is not yet availabe, setup it and return it
   *
   * @return   Twig_Environment
   */
  public static function getTwigEnvironment()
  {
    if (null === self::$twig_environment)
    {
      self::$twig_environment = new Twig_Environment(self::getTwigLoader(), array(
         'cache' => sfConfig::get('sf_cache_dir') . '/twig/email_templates_cache',
      ));

      self::registerSymfonyHelpers(self::$twig_environment);
    }

    return self::$twig_environment;
  }

  public static function getTwigStringEnvironment()
  {
    if (null === self::$twig_string_environment)
    {
      self::$twig_string_environment = new Twig_Environment(new Twig_Loader_String());

      self::registerSymfonyHelpers(self::$twig_string_environment);
    }

    return self::$twig_string_environment;
  }

  /**
   * Add the configured symfony helpers to the twig environmet
   *
   * @param Twig_Environment $env
   */
  protected static function registerSymfonyHelpers(Twig_Environment $env)
  {
    $helpers = sfConfig::get('app_cqEmails_helpers', array());

    /** @var $configuration sfApplicationConfiguration */
    $configuration = sfProjectConfiguration::getActive();
    $configuration->loadHelpers(array_keys($helpers));

    foreach ($helpers as $helper) foreach ((array) $helper as $function)
    {
      $env->addFunction($function, new Twig_Function_Function($function, array(
          'is_safe' => array('html'),
      )));
    }
  }

  /**
   * Get the data array for an email template
   *
   * @param     string $name
   * @return    array
   */
  public static function getDataForName($name)
  {
    $parts = preg_split('#(/|:)#', $name, -1, PREG_SPLIT_NO_EMPTY);

    if (count($parts) > 1)
    {
      $namespace = array_shift($parts);
      $namespace_data = sfConfig::get('app_cqEmails_'.$namespace, array());

      $data = isset($namespace_data[implode('_', $parts)])
        ? $namespace_data[implode('_', $parts)]
        : array();
    }
    else
    {
      $data = sfConfig::get('app_cqEmails_' . $name, array());
    }

    return array_merge(
      sfConfig::get('app_cqEmails_defaults', array()),
      $data);
  }

  /**
   * Get a specific option from the data array of an email template
   *
   * @param     string $name
   * @param     string $option
   * @param     mixed $default
   * @return    mixed
   */
  public static function getOptionForName($name, $option, $default = null)
  {
    $data = self::getDataForName($name);

    return isset($data[$option])
      ? $data[$option]
      : $default;
  }

}
