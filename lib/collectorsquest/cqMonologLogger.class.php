<?php

use Monolog\Logger;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\FingersCrossedHandler;

class cqMonologLogger extends sfLogger
{

  /** @var Monolog\Logger */
  protected $logger = null;

  /** @var Monolog\Handler\HandlerInterface[] */
  protected $handlers = array();

  /** @var integer */
  protected $level = self::CRIT;

  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    if (!isset($options['handlers']) || !is_array($options['handlers']))
    {
      throw new sfConfigurationException('You must provide the "handlers" parameter for this logger.');
    }

    $this->handlers = array();
    foreach ($options['handlers'] as $name => $params)
    {
      if (in_array($params['type'], array('fingers_crossed', 'group'))) {
        continue;
      }

      $this->handlers[$name] = $this->createHandler($name, $params);
      unset($options['handlers'][$name]);
    }

    foreach ($options['handlers'] as $name => $params)
    {
      $this->handlers[$name] = $this->createHandler($name, $params);
      unset($options['handlers'][$name]);
    }


//    $this->handlers = array_reverse($this->handlers);
//    uasort($this->handlers, function($a, $b)
//    {
//      if ($a['priority'] == $b['priority']) {
//        return 0;
//      }
//
//      return $a['priority'] < $b['priority'] ? -1 : 1;
//    });

    // Create the logger
    $this->logger = new Logger('symfony');

    foreach ($this->handlers as $handler)
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
      switch ($priority)
      {
        case self::ALERT:
          $this->logger->addAlert($message);
          break;
        case self::CRIT:
        case self::EMERG:
          $this->logger->addCritical($message);
          break;
        case self::ERR:
          $this->logger->addError($message);
          break;
        case self::WARNING:
          $this->logger->addWarning($message);
          break;
        case self::NOTICE:
        case self::INFO:
          $this->logger->addInfo($message);
          break;
        case self::DEBUG:
          $this->logger->addDebug($message);
          break;
      }
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
    $handler = null;
    $params['level'] = is_int($params['level']) ? $params['level'] : constant('Monolog\Logger::'.strtoupper($params['level']));

    switch ($params['type'])
    {
      case 'fingers_crossed':
        $params['action_level'] = is_int($params['action_level']) ? $params['action_level'] : constant('Monolog\Logger::'.strtoupper($params['action_level']));

        $handler = new Monolog\Handler\FingersCrossedHandler(
          $this->handlers[$params['handler']],
          $params['action_level'],
          $params['buffer_size'],
          $params['bubble'],
          $params['stop_buffering']
        );

        break;

      case 'stream':
        $handler = new Monolog\Handler\StreamHandler(
          $params['path'], $params['level'], $params['bubble']
        );
        break;

      case 'swift_mailer':
        $mailer = cqContext::getInstance()->getMailer();
        $message = $mailer->compose($params['from_email'], $params['to_email'], $params['subject']);
        $handler = new Monolog\Handler\SwiftMailerHandler($mailer, $message, $params['level']);
        break;

      case 'group':
        $_handlers = array();
        foreach ($params['members'] as $name)
        {
          $_handlers[] = $this->handlers[$name];
          unset($this->handlers[$name]);
        }

        $handler = new Monolog\Handler\GroupHandler($_handlers, @$params['bubble']);
        break;

      default:
        throw new \InvalidArgumentException(sprintf('Invalid handler type "%s" given for handler "%s"', $params['type'], $name));
        break;
    }

    return $handler;
  }

}
