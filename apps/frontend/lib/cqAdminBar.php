<?php

class cqAdminBar
{
  private static $instance = null;
  private $objects_menu = array();
  private $application = null;

  public function __construct(frontendConfiguration $application)
  {
    $this->application = $application;
  }

  /**
   * @return cqAdminBar
   */
  private static function getInstance()
  {
    if (is_null(self::$instance))
    {
      /** @var $application backendConfiguration */
      $application = sfProjectConfiguration::getActive();
      self::$instance = new cqAdminBar($application);
    }
    return self::$instance;
  }

  private function addObject($object)
  {
    $this->objects_menu[$this->generateUrl($object)] = $object->__toString();
  }

  /**
   * generate url for backend
   */
  private function generateUrl($object)
  {
    $route_name = strtolower(get_class($object));
    return $this->application->generateBackendUrl($route_name . '_edit', array('sf_subject'=>$object));
  }

  public function getObjectMenu()
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
    return self::getInstance()->getObjectMenu();
  }

  /**
   * Event listener for show object event
   *
   * @param sfEvent $event
   */
  public static function listenShowObject(sfEvent $event){
    /** @var $parameters array */
    $parameters = $event->getParameters();
    self::getInstance()->addObject($parameters['object']);
  }
}
