<?php

class cqMailer extends Swift_Mailer
{
  const
    REALTIME       = 'realtime',
    SPOOL          = 'spool',
    SINGLE_ADDRESS = 'single_address',
    NONE           = 'none';

  protected $avoidSingleAddressIdentifier = '@collectorsquest.com';

  protected
    $spool             = null,
    $logger            = null,
    $strategy          = 'realtime',
    $address           = '',
    $realtimeTransport = null,
    $force             = false,
    $redirectingPlugin = null,
    $forceRedirectOfCollectorsquestEmails = false;

  /**
   * Constructor.
   *
   * @param     sfEventDispatcher  $dispatcher  An event dispatcher instance
   * @param     array  $options  An array of options
   *
   * @throws InvalidArgumentException
   */
  public function __construct(sfEventDispatcher $dispatcher, $options)
  {
    // options
    $options = array_merge(array(
      'charset' => 'UTF-8',
      'logging' => false,
      'delivery_strategy' => 'realtime',
      'transport' => array(
        'class' => 'Swift_MailTransport',
        'param' => array(),
       ),
      'force_redirect_of_collectorsquest_emails' => false,
    ), $options);

    $constantName = 'sfMailer::'.strtoupper($options['delivery_strategy']);
    $this->strategy = defined($constantName) ? constant($constantName) : false;
    if (!$this->strategy)
    {
      throw new InvalidArgumentException(sprintf(
        'Unknown mail delivery strategy "%s" (should be one of realtime, spool, single_address, or none)',
        $options['delivery_strategy']
      ));
    }

    /* @var $class string */
    $class = $options['transport']['class'];

    /* @var $transport Swift_MailTransport */
    $transport = new $class();

    if (isset($options['transport']['param']))
    {
      foreach ($options['transport']['param'] as $key => $value)
      {
        $method = 'set'.ucfirst($key);
        if (method_exists($transport, $method))
        {
          $transport->$method($value);
        }
        elseif (method_exists($transport, 'getExtensionHandlers'))
        {
          foreach ($transport->getExtensionHandlers() as $handler)
          {
            if (in_array(strtolower($method), array_map('strtolower', (array) $handler->exposeMixinMethods())))
            {
              $transport->$method($value);
            }
          }
        }
      }
    }

    $this->realtimeTransport = $transport;

    if (sfMailer::SPOOL == $this->strategy)
    {
      if (!isset($options['spool_class']))
      {
        throw new InvalidArgumentException(
          'For the spool mail delivery strategy, you must also define a spool_class option'
        );
      }
      $arguments = isset($options['spool_arguments']) ? $options['spool_arguments'] : array();

      if ($arguments)
      {
        $r = new ReflectionClass($options['spool_class']);
        $this->spool = $r->newInstanceArgs($arguments);
      }
      else
      {
        $this->spool = new $options['spool_class'];
      }

      $transport = new Swift_SpoolTransport($this->spool);
    }
    elseif (sfMailer::SINGLE_ADDRESS == $this->strategy)
    {
      if (!isset($options['delivery_address']))
      {
        throw new InvalidArgumentException(
          'For the single_address mail delivery strategy, you must also define a delivery_address option'
        );
      }
      $this->forceRedirectOfCollectorsquestEmails = $options['force_redirect_of_collectorsquest_emails'];

      $this->address = $options['delivery_address'];

      $this->redirectingPlugin = new cqSwiftPluginsRedirectingPlugin(
        $this->address,
        array($this, 'shouldMessageUseRedirectingPlugin')
      );
      $transport->registerPlugin($this->redirectingPlugin);
    }

    parent::__construct($transport);

    // logger
    if (isset($options['logging']) && $options['logging'] === true)
    {
      $transport->registerPlugin(new cqEmailLogger());
    }

    if (sfMailer::NONE == $this->strategy)
    {
      // must be registered after logging
      $transport->registerPlugin(new Swift_Plugins_BlackholePlugin());
    }

    // preferences
    Swift_Preferences::getInstance()->setCharset($options['charset']);

    $dispatcher->notify(new sfEvent($this, 'mailer.configure'));
  }

  /**
   * Gets the realtime transport instance.
   *
   * @return Swift_Transport The realtime transport instance.
   */
  public function getRealtimeTransport()
  {
    return $this->realtimeTransport;
  }

  /**
   * Sets the realtime transport instance.
   *
   * @param Swift_Transport $transport The realtime transport instance.
   */
  public function setRealtimeTransport(Swift_Transport $transport)
  {
    $this->realtimeTransport = $transport;
  }

  /**
   * Gets the logger instance.
   *
   * @return sfMailerMessageLoggerPlugin The logger instance.
   */
  public function getLogger()
  {
    return $this->logger;
  }

  /**
   * Sets the logger instance.
   *
   * @param sfMailerMessageLoggerPlugin $logger The logger instance.
   */
  public function setLogger($logger)
  {
    $this->logger = $logger;
  }

  /**
   * Gets the delivery strategy.
   *
   * @return string The delivery strategy
   */
  public function getDeliveryStrategy()
  {
    return $this->strategy;
  }

  /**
   * Gets the delivery address.
   *
   * @return string The delivery address
   */
  public function getDeliveryAddress()
  {
    return $this->address;
  }

  /**
   * Sets the delivery address.
   *
   * @param string $address The delivery address
   */
  public function setDeliveryAddress($address)
  {
    $this->address = $address;

    if (sfMailer::SINGLE_ADDRESS == $this->strategy)
    {
      $this->redirectingPlugin->setRecipient($address);
    }
  }

  /**
   * Creates a new message.
   *
   * @param string|array $from    The from address
   * @param string|array $to      The recipient(s)
   * @param string       $subject The subject
   * @param string       $body    The body
   *
   * @return Swift_Message A Swift_Message instance
   */
  public function compose($from = null, $to = null, $subject = null, $body = null)
  {
    return Swift_Message::newInstance()
      ->setFrom($from)
      ->setTo($to)
      ->setSubject($subject)
      ->setBody($body)
    ;
  }

  /**
   * Sends a message.
   *
   * @param string|array $from    The from address
   * @param string|array $to      The recipient(s)
   * @param string       $subject The subject
   * @param string       $body    The body
   *
   * @return int The number of sent emails
   */
  public function composeAndSend($from, $to, $subject, $body)
  {
    return $this->send($this->compose($from, $to, $subject, $body));
  }

  /**
   * Forces the next call to send() to use the realtime strategy.
   *
   * @return sfMailer The current sfMailer instance
   */
  public function sendNextImmediately()
  {
    $this->force = true;

    return $this;
  }

  /**
   * Sends the given message.
   *
   * @param     Swift_Mime_Message  $message
   * @param     string[]  &$failedRecipients An array of failures by-reference
   *
   * @return    integer|boolean The number of sent emails
   */
  public function send(Swift_Mime_Message $message, &$failedRecipients = null)
  {
    if ($this->force)
    {
      $this->force = false;

      if (!$this->realtimeTransport->isStarted())
      {
        $this->realtimeTransport->start();
      }

      return $this->realtimeTransport->send($message, $failedRecipients);
    }
    return parent::send($message, $failedRecipients);
  }

  public function shouldMessageUseRedirectingPlugin(Swift_Mime_Message $message)
  {
    $addresses = array_keys($message->getTo());
    $addresses = implode(',', $addresses);

    return false === strpos($addresses, $this->avoidSingleAddressIdentifier) ||
      $this->forceRedirectOfCollectorsquestEmails;
  }

  /**
   * Sends the current messages in the spool.
   *
   * The return value is the number of recipients who were accepted for delivery.
   *
   * @param string[] &$failedRecipients An array of failures by-reference
   *
   * @return int The number of sent emails
   */
  public function flushQueue(&$failedRecipients = null)
  {
    return $this->getSpool()->flushQueue($this->realtimeTransport, $failedRecipients);
  }

  public function getSpool()
  {
    if (self::SPOOL != $this->strategy)
    {
      throw new LogicException(sprintf(
        'You can only send messages in the spool if the delivery strategy is "spool" (%s is the current strategy).',
        $this->strategy
      ));
    }

    return $this->spool;
  }

  static public function initialize()
  {
    require_once sfConfig::get('sf_symfony_lib_dir').'/vendor/swiftmailer/swift_init.php';
  }
}
