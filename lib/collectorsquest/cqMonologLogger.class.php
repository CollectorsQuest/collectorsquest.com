<?php

use Monolog\Logger;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\FingersCrossedHandler;

class cqMonologLogger extends sfLogger
{

  /** @var Monolog\Logger */
  protected $logger = null;

  /** @var integer */
  protected $level = self::CRIT;

  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    if (!isset($options['handlers']) || !is_array($options['handlers']))
    {
      throw new sfConfigurationException('You must provide the "handlers" parameter for this logger.');
    }

    $handlers = array();
    foreach ($options['handlers'] as $name => $params)
    {
      $handlers[$name] = $this->createHandler($name, $params);
    }

    $handlers = array_reverse($handlers);
    uasort($handlers, function($a, $b)
    {
      if ($a['priority'] == $b['priority']) {
        return 0;
      }

      return $a['priority'] < $b['priority'] ? -1 : 1;
    });

    // Create the logger
    $this->logger = new Logger('symfony');

    foreach ($handlers as $handler)
    {
      $this->logger->pushHandler($handler);
    }

    return parent::initialize($dispatcher, $options);
  }

  /**
   * Logs a message.
   *
   * @param string $message   Message
   * @param string $priority  Message priority
   */
  protected function doLog($message, $priority)
  {
    if (null !== $this->logger)
    {
      $this->logger->addInfo($message);
    }
  }

  /**
   * Returns the priority string to use in log messages.
   *
   * @param  string $priority The priority constant
   *
   * @return string The priority to use in log messages
   */
  protected function getPriority($priority)
  {
    return sfLogger::getPriorityName($priority);
  }

  private function createHandler($name, array $params)
  {
    $params['level'] = is_int($params['level']) ? $params['level'] : constant('sfLogger::'.strtoupper($params['level']));

    switch ($params['type'])
    {
      case 'stream':
        $handler = new Monolog\Handler\StreamHandler(
          $params['path'], $params['level'], $params['bubble']
        );
        break;

      default:
        throw new \InvalidArgumentException(sprintf('Invalid handler type "%s" given for handler "%s"', $params['type'], $name));
        break;
    }

    return $handler;
  }

}
