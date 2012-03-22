<?php

/**
 * Description of cqEmailConfig
 */
class cqEmailsConfig
{

  static $twig_loader;
  static $twig_environment;


  /**
   * @return    string
   */
  public static function getTemplatesDir()
  {
    return realpath(dirname(__FILE__).'/../../email_templates/');
  }

  /**
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

  protected static function registerSymfonyHelpers(Twig_Environment $env)
  {
    $helpers = array(
        'Tag' => array(
        ),
        'Url' => array(
            'link_to',
            'url_for',
        ),
    );

    $configuration = sfProjectConfiguration::getActive();
    $configuration->loadHelpers(array_keys($helpers));

    foreach ($helpers as $helper) foreach ($helper as $function)
    {
      $env->addFunction($function, new Twig_Function_Function($function, array(
          'is_safe' => array('html'),
      )));
    }
  }



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

  public static function getOptionForName($name, $option, $default = null)
  {
    $data = self::getDataForName($name);

    return isset($data[$option])
      ? $data[$option]
      : $default;
  }

}
