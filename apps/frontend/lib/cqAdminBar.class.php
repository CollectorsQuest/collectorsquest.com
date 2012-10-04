<?php

class cqAdminBar
{
  private static $instance = null;
  private $application = null;
  private $founded_routes = array();
  private $objects_edit_menu = array();

  public function __construct(frontendConfiguration $application)
  {
    $this->application = $application;
  }

  /**
   * get Instance
   *
   * @return cqAdminBar
   */
  private static function getInstance()
  {
    if (is_null(self::$instance))
    {
      /** @var $application frontendConfiguration */
      $application = sfProjectConfiguration::getActive();
      self::$instance = new cqAdminBar($application);
    }

    return self::$instance;
  }

  /**
   * Add new object to admin bar
   *
   * @param $object
   */
  private function addObject($object)
  {
    if ($url = $this->generateBackendEditUrl($object))
    {
      /** @var $group string */
      $group = 'Edit ';
      $this->objects_edit_menu[$group][$url] =
        sfToolkit::pregtr(get_class($object), array('/([A-Z]+)([A-Z][a-z])/' => '\\1 \\2',
                                                    '/([a-z\d])([A-Z])/'     => '\\1 \\2'));
    }
  }

  /**
   * Generate edit url for backend
   *
   * @param $object
   * @return bool|string
   */
  private function generateBackendEditUrl($object)
  {
    /** @var $routing sfRoute[] */
    $routing = $this->application->getBackendRouting()->getRoutes();
    /** @var $route_name string */
    $route_name = null;
    if (isset($this->founded_routes[get_class($object)]))
    {
      $route_name = $this->founded_routes[get_class($object)];
    }
    else
    {
      // Trying to found backend route foe edit action
      foreach ($routing as $key => $route)
      {
        $options = $route->getOptions();
        if (
          isset($options['type']) && $options['type'] == 'object'
          && isset($options['model']) && $options['model'] == get_class($object)
          && substr($key, -5, 5) == '_edit'
        )
        {
          $route_name = $this->founded_routes[get_class($object)] = $key;

          break;
        }
      }
    }

    return $route_name !== null
      ? $this->application->generateBackendUrl($route_name, array('sf_subject'=>$object))
      : false;
  }

  /**
   * Get Object edit menu items
   *
   * @return array
   */
  public function getObjectsEditMenu()
  {
    return $this->objects_edit_menu;
  }

  /**
   * Return array of menu items
   *
   * @return array
   */
  public static function getMenu()
  {
    return self::getInstance()->getObjectsEditMenu();
  }

  /**
   * Event listener for show object event
   *
   * @param sfEvent $event
   */
  public static function listenShowObject(sfEvent $event){
    /** @var $parameters array */
    $parameters = $event->getParameters();
    if (is_object($parameters['object']))
    {
      self::getInstance()->addObject($parameters['object']);
    }
  }
}
