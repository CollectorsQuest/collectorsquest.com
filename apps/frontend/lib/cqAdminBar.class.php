<?php

class cqAdminBar
{
  protected static $instance = null;
  protected $application = null;
  protected $object_params = array();
  protected $objects_menu = array();

  public function __construct(frontendConfiguration $application)
  {
    $this->application = $application;
  }

  /**
   * get Instance
   *
   * @return cqAdminBar
   */
  public static function getInstance()
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
   * @param BaseObject $object
   */
  protected function addObject($object)
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
              $url = $this->application->generateBackendUrl(
                $route_name . '_edit', array('sf_subject'=>$object)
              );
              $this->addMenuItem('Actions', array(
                  'label' => 'Edit',
                  'url' => $url,
                  'attributes' => array(
                      'target' => '_blank',
                  ),
              ));
              break;
            default:
              $action = isset($param['action']) ? $param['action'] : 'List' . ucfirst($action);
              $label = isset($param['label']) ? $param['label']
                  : sfToolkit::pregtr($action, array('/([A-Z]+)([A-Z][a-z])/' => '\\1 \\2',
                                                     '/([a-z\d])([A-Z])/'     => '\\1 \\2'));;
              $a_params = array_merge(
                  array('target' => '_blank'), isset($param['params']) ? $param['params'] : array()
              );
              $url = $this->application->generateBackendUrl(
                  $route_name.'_object', array('sf_subject' => $object, 'action' => $action)
              );
              $this->addMenuItem('Actions', array(
                  'label' => $label,
                  'url' => $url,
                  'attributes' => $a_params,
              ));
          }
        }
      }
      else
      {
        $url = $this->application->generateBackendUrl(
          $route_name . '_edit', array('sf_subject'=>$object)
        );
        $this->addMenuItem('Edit', array(
            'label' => $label,
            'url' => $url,
            'attributes' => array(
                'target' => '_blank',
            ),
        ));
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

      $this->addMenuItem('Rating', array(
        'label' => $label,
        'url' => $url,
        'info' => sprintf('(%s)', number_format($object->getAverageRating(), 1) ?: 'n/a'),
        'attributes' => array(
            'onclick' => 'return false;',
            'class' => 'open-dialog',
            'title' => 'Rating for ' . $object
        ),
      ));
    }

    // Limitation for the first version
    if (in_array(get_class($object), array('CollectorCollection', 'Collection', 'Collectible')))
    {
      $url = $this->application->generateBackendUrl(
        'object_machine_tags',
        array('class' => get_class($object), 'id' => $object->getId())
      );

      $this->addMenuItem('Machine Tags', array(
        'label' => $label,
        'url' => $url,
        'info' => sprintf('(%s)', count($object->getTags(array('is_triple' => true, 'return' => 'tag')))),
        'attributes' => array(
            'onclick' => 'return false;',
            'class' => 'open-dialog',
            'title' => 'Machine Tags for ' . $object
        ),
      ));
    }

    // Limitation for the first version
    if (in_array(get_class($object), array('CollectorCollection', 'Collection', 'Collectible')))
    {
      $url = $this->application->generateBackendUrl(
        'object_is_public',
        array('class' => get_class($object), 'id' => $object->getId())
      );

      $this->addMenuItem('Make ' . ($object->getIsPublic() ? 'Private' : 'Public'), array(
        'label' => $label,
        'url' => $url,
        'attributes' => array(
            'onclick' => 'return false;',
            'class' => 'open-dialog',
            'title' => 'Change visibility status for ' . $object
        ),
      ));
    }
  }

  /**
   * Get backend parameters for object
   * such as object_actions and route name
   *
   * @param $object
   * @return array|null
   */
  protected function getObjectBackendParameters($object)
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

  /**
   * Add a new menu item for a specific menu
   *
   * Menu items are represented by an array and must have a "label"
   *
   * menu item fields:
   *   - label
   *   - info
   *   - url
   *   - attributes (html attributes)
   *
   * @param     string $menu_name
   * @param     array $item_data
   * @return    cqAdminBar
   *
   * @throws    RuntimeException When there are missing required fields for an item
   */
  public function addMenuItem($menu_name, $item_data)
  {
    if (!isset($item_data['label']))
    {
      throw new RuntimeException(spritnf(
        '[cqAdminBar] Menu items must hava a label. Data given: %s',
        print_r($item_data, true)
      ));
    }

    if (!isset($item_data['url']))
    {
      $item_data['url'] = '#';
    }

    $item_data = sfToolkit::arrayDeepMerge(array('attributes' => array('href' => $item_data['url'])), $item_data);

    $this->objects_menu[$menu_name][] = $item_data;

    return $this;
  }

}
