<?php

class cqAdminBar
{
  private static $instance = null;
  private $application = null;
  private $found_routes = array();
  private $object_menu = array();

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
    $label = sfToolkit::pregtr(get_class($object), array('/([A-Z]+)([A-Z][a-z])/' => '\\1 \\2',
                                                         '/([a-z\d])([A-Z])/'     => '\\1 \\2'));
    if ($url = $this->generateBackendEditUrl($object))
    {
      /** @var $group string */
      $group = 'Edit ';
      $this->objects_menu[$group][$url] =
        array(
          'label' => $label,
          'attributes' => array('target' => '_blank', 'href' => $url),
      );
    }
    //no method - no rating
    if (method_exists($object, 'getAverageRate'))
    {
      $backend_user_id = sfContext::getInstance()->getRequest()->getCookie('cq_bc');
      $this->objects_menu['Rate'][$url = $this->application->generateBackendUrl(
        'object_rate', array('class' => get_class($object), 'id' => $object->getId(), 'bc' => $backend_user_id)
      )] =
        array(
          'label' => $label,
          'attributes' => array(
            'onclick' => 'return false;', 'href' => $url,
            'class' => 'open-dialog', 'title' => 'Rate ' . $object
          ),
        );
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
    if (isset($this->found_routes[get_class($object)]))
    {
      $route_name = $this->found_routes[get_class($object)];
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
          $route_name = $this->found_routes[get_class($object)] = $key;

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
  public function getObjectsMenu()
  {
    return $this->objects_menu;
  }

  /**
   * Return array of menu items
   *
   * @return array
   */
  public static function getMenu()
  {
    return self::getInstance()->getObjectsMenu();
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