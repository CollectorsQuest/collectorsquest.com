<?php

class cqAdminBar
{
  private static $instance = null;
  private $application = null;
  private $object_params = array();
  private $objects_menu = array();

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
      /* @var $application frontendConfiguration */
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
    if ($params = $this->getObjectBackendParameters($object))
    {
      $route_name = $params['route'];
      if (count($params['actions']))
      {
        foreach ($params['actions'] as $action => $param)
        {
          switch ($action)
          {
            case '_delete':
                //no delete for now
                  break;

            case '_edit':
                $this->objects_menu['Actions'][] =
                    array(
                        'label' => 'Edit',
                        'attributes' => array(
                            'target' => '_blank',
                            'href' => $this->application->generateBackendUrl(
                                $route_name . '_edit', array('sf_subject'=>$object)
                            )
                        ),
                    );
                  break;
            default:
              $action = isset($param['action']) ? $param['action'] : 'List' . ucfirst($action);
              $label = isset($param['label']) ? $param['label']
                  : sfToolkit::pregtr($action, array('/([A-Z]+)([A-Z][a-z])/' => '\\1 \\2',
                                                     '/([a-z\d])([A-Z])/'     => '\\1 \\2'));;
              $a_params = array_merge(
                  array('target' => '_blank'), isset($param['params']) ? $param['params'] : array()
              );
              $a_params['href'] = $this->application->generateBackendUrl(
                  $route_name.'_object', array('sf_subject' => $object, 'action' => $action)
              );
              $this->objects_menu['Actions'][] =
                  array(
                      'label' => $label,
                      'attributes' => $a_params,
                  );
          }
        }
      }
      else
      {
        $this->objects_menu['Edit'][] =
            array(
                'label' => $label,
                'attributes' => array(
                    'target' => '_blank',
                    'href' => $this->application->generateBackendUrl(
                        $route_name . '_edit', array('sf_subject'=>$object)
                    )
                ),
            );
      }

    }

    // no method - no rating
    if (method_exists($object, 'getAverageRating'))
    {
      $url = $this->application->generateBackendUrl(
        'object_rating', array(
          'class' => get_class($object), 'id' => $object->getId()
        )
      );

      $this->objects_menu['Rating'][] = array(
        'label' => $label,
        'url' => $url,
        'info' => sprintf('(%s)', number_format($object->getAverageRating(), 1) ?: 'n/a'),
        'attributes' => array(
          'onclick' => 'return false;', 'href' => $url,
          'class' => 'open-dialog', 'title' => 'Rating for ' . $object
        )
      );
    }

    // Limitation for the first version
    if (
      cqGateKeeper::open('machine_tags') &&
      in_array(get_class($object), array('CollectorCollection', 'Collection', 'Collectible'))
    )
    {
      $url = $this->application->generateBackendUrl(
        'object_machine_tags', array(
          'class' => get_class($object), 'id' => $object->getId()
        )
      );

      $this->objects_menu['Machine Tags'][] = array(
        'label' => $label,
        'url' => $url,
        'info' => sprintf('(%s)', count($object->getTags(array('is_triple' => true, 'return' => 'tag')))),
        'attributes' => array(
          'onclick' => 'return false;', 'href' => $url,
          'class' => 'open-dialog', 'title' => 'Machine Tags for ' . $object
        )
      );
    }
  }

  /**
   * Get backend parameters for object
   * such as object_actions and route name
   *
   * @param $object
   * @return array|null
   */
  private function getObjectBackendParameters($object)
  {
    if (isset($this->object_params[get_class($object)]))
    {
       return $this->object_params[get_class($object)];
    }
    $this->object_params[get_class($object)] = null;
    /* @var $routing sfRoute[] */
    $routing = $this->application->getBackendRouting()->getRoutes();
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
          $route_defaults = $route->getDefaults();
          $generator = sfYaml::load(
              sfConfig::get('sf_apps_dir')
                  . DIRECTORY_SEPARATOR . 'backend'
                  . DIRECTORY_SEPARATOR . 'modules'
                  . DIRECTORY_SEPARATOR . $route_defaults['module']
                  . DIRECTORY_SEPARATOR . 'config'
                  . DIRECTORY_SEPARATOR . 'generator.yml'
          );
          $actions = isset($generator['generator']['param']['config']['list']['object_actions'])
              ? $generator['generator']['param']['config']['list']['object_actions'] : array();

          $this->object_params[get_class($object)] = array(
              'route' => preg_replace('/(_edit)$/', '', $key), 'actions' => $actions
          );

          return $this->object_params[get_class($object)];
         }
    }

    return $this->object_params[get_class($object)];
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
  public static function listenShowObject(sfEvent $event)
  {
    /* @var $parameters array */
    $parameters = $event->getParameters();

    if (is_object($parameters['object']))
    {
      self::getInstance()->addObject($parameters['object']);
    }
  }
}
