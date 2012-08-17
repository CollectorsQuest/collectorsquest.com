<?php

/**
 * Util class used with cqLinksHelper
 */
class cqLinkUtils
{
  /** @var cqLinkUtils */
  protected static $instance = null;

  /**
   * Get an instance of the class
   *
   * @return cqLinkUtils
   */
  public static function getInstance()
  {
    if (null === self::$instance)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * @var array Temporary variable, required because of the way
   *            configs are cached in symfony
   */
  protected $security = array();

  /** @var array Cached module security configs */
  protected $modules_security = array();

  /**
   * Get the security configuration for a module, that looks like:
   * array(
   *   'action' => true,
   *   'all'    => false,
   * );
   *
   * @param     string $module
   * @return    array
   */
  public function getSecurityForModule($module)
  {
    // if we do not have the cached config for this module
    if (!isset($this->modules_security[$module]))
    {
      // first reset the temp variable
      $this->security = array('all' => false);

      // then try to get the file (or generate if necessary)
      $file = cqContext::getInstance()->getConfigCache()->checkConfig(
        'modules/'.$module.'/config/security.yml', true
      );

      // if the file exists
      if ($file)
      {
        // load it.
        // Security files contain code like:
        // $this->security = array('action' => true/false);
        // so the security configuration is loaded in the $security instance var
        require($file);
      }

      // save the information from the temp value into our local cache
      $this->modules_security[$module] = $this->security;
    }

    // return the security information for the module
    return $this->modules_security[$module];
  }

  /**
   * Checks if a specific module/action combo is secure.
   *
   * If the action does not have a security setting,
   * the "all" value for the module is returned.
   * If there is none, the application-wide security setting is returned
   *
   * @param     string $module
   * @param     string $action
   *
   * @return    boolean
   */
  public function isSecureModuleAction($module, $action)
  {
    $module_security = $this->getSecurityForModule($module);

    return isset($module_security[$action]['is_secure'])
      ? $module_security[$action]['is_secure']
      : $module_security['all']['is_secure'];
  }

  /**
   * Try to asses if a route is secured
   *
   * @see       sfWebController::genUrl()
   * @see       sfWebController::convertUrlStringToParameters()
   *
   * @param     mixed $parameters
   *
   * @throws    Exception
   * @return    boolean
   */
  public function isSecureRoute($parameters = array())
  {
    $route = '';

    if (is_string($parameters))
    {
      // absolute URL or symfony URL?
      if (preg_match('#^[a-z][a-z0-9\+.\-]*\://#i', $parameters))
      {
        // those will *generally* not be secure - ie, they will point to an
        // external resource. If the URL is an actual absolute URL
        // to our application the result might be a false negative,
        // but it's not worth to check for that
        return false;
      }

      // relative URL?
      if (0 === strpos($parameters, '/'))
      {
        // now relative urls are 100% pointing to our application,
        // and in this case we throw an exception
        throw new Exception(sprintf(
          '[cqLinkUtils] Cannot asses if a route is secure for relative routes "%s"',
          $parameters
        ));
      }

      if ($parameters == '#')
      {
        // not an actual url but probably an empty javascript anchor
        return false;
      }

      // strip fragment
      if (false !== ($pos = strpos($parameters, '#')))
      {
        $parameters = substr($parameters, 0, $pos);
      }

      /** @var $sf_controller sfWebController */
      $sf_controller = cqContext::getInstance()->getController();

      list($route, $parameters) = $sf_controller->convertUrlStringToParameters($parameters);
    }
    else if (is_array($parameters))
    {
      if (isset($parameters['sf_route']))
      {
        $route = $parameters['sf_route'];
        unset($parameters['sf_route']);
      }
    }

    // if the route is not a default route like:
    //   url:   /:module/:action/*
    // then we need to get the parameters from the named route
    if ( !(isset($parameters['module']) || isset($parameters['action'])) )
    {
      // get all routes
      $routes = cqContext::getInstance()->getRouting()->getRoutes();

      // check if a named route exists
      if (!isset($routes[$route]))
      {
        throw new Exception(sprintf(
          '[cqLinkUtils] No route "%s" is defined in routing.yml',
          $route
        ));
      }

      // reset the parameters to the named route's defaults
      $parameters = $routes[$route]->getDefaults();
    }

    return $this->isSecureModuleAction(
      $parameters['module'],
      $parameters['action']
    );
  }

}
