<?php

/**
 * ComposePrivateMessageForm does what its name says :)
 *
 * Options:
 *    attach: Collection/Collectible object
 *
 */
class ComposePrivateMessageForm extends PrivateMessageForm
{

  /** @var Collector */
  protected $sender_collector;

  /** @var string */
  protected $thread;

  /** @var cqFrontendUser */
  protected $sf_user;

  /** @var array Allowed attach fields */
  public static $attach_fields = array(
      'collection_id' => 'Collection',
      'collectible_id' => 'Collectible',
      'shopping_order_id' => 'ShoppingOrder',
  );

  /**
   * The Compose form takes a Collector object in its constructor that
   * will be set the "sender"
   *
   * If the $sf_user parameter is supplied, the form will check how many messages
   * has the user sent in the current session and append a captcha field
   * if over the threshold
   *
   * @param     Collector   $sender
   * @param     cqBaseUser  $sf_user
   * @param     string      $thread
   * @param     array       $options
   * @param     string      $CSRFSecret
   */
  public function __construct(
    Collector $sender,
    $thread = null,
    $options = array(),
    $CSRFSecret = null
  ) {
    $this->sender_collector = $sender;
    $this->thread = $thread;
    $this->sf_user = cqContext::getInstance()->getUser();

    parent::__construct(null, $options, $CSRFSecret);
  }

  public function configure()
  {
    parent::configure();

    $this->setupReceiverField();
    $this->setupSenderField();
    $this->setupThreadField();
    $this->setupRedirectField();
    $this->setupAttachFields();
    $this->setupCopyForSenderField();
    $this->setupIpAddressField();

    $this->unsetFields();

    $this->widgetSchema->setLabels(array(
        'receiver' => 'To',
        'body' => 'Message',
        'copy_for_sender' => 'Email me a copy',
    ));

    $this->widgetSchema->setPositions(array_merge(array_keys(self::$attach_fields), array(
        'ip_address',
        'thread',
        'goto',
        'sender',
        'receiver',
        'subject',
        'body',
        'copy_for_sender',
    )));

    $this->setupCaptchaField();

    $this->mergePostValidator(new iceSpamControlValidatorSchema(array(
        'credentials' => iceSpamControl::CREDENTIALS_ALL,
        'fields' => array(
            $this->getIpAddressFieldName() => 'ip',
        ),
        'force_skip_check' => array($this, 'forceSkipSpamCheck'),
      ), array(
        'spam' => 'We are sorry we could not send your private message. Please try again later.',
    )));

    $this->mergePostValidator(new AyahValidatorSchema(array(), array(
        'spam' => 'We are sorry we could not send your private message. Please try again later.',
    )));

    $this->mergePostValidator(new cqValidatorSchemaTimeoutCheck($this->sf_user, array(
        'type' => cqValidatorSchemaTimeoutCheck::TIMEOUT_TYPE_PRIVATE_MESSAGES,
        'threshold' => sfConfig::get('app_private_messages_timeout_threshold', 6),
        'timeout_duration' => sfConfig::get('app_private_messages_timeout_duration', '30 minutes'),
        'timeout_check_period' => sfConfig::get('app_private_messages_timeout_check_period', '60 minutes'),
        'force_skip_check' => array($this, 'forceSkipSpamCheck'),
    )));
  }

  /**
   * Callback used to skip spam and timeout checks for specific users
   *
   * @return boolean
   */
  public function forceSkipSpamCheck()
  {
    if ($this->sf_user->isAdmin())
    {
      // if the user is logged in the backend as admin, skip spam check
      return true;
    }

    if ( $collector = $this->sf_user->getCollector($strict = true) )
    {
      // skip if current user is one of the predefined skip spam users
      if (in_array($collector->getUsername(), sfConfig::get('app_skip_spam_check_by_username', array())))
      {
        return true;
      }
    }

    return false;
  }

  protected function setupReceiverField()
  {
    // check if we are replying to a thread
    if (null !== $this->thread)
    {
      // if yes, then the receiver field needs to be hidden
      $this->widgetSchema['receiver'] = new sfWidgetFormInputHidden();
    }
    else
    {
      // if no, then allow the user to input the receiver
      $this->widgetSchema['receiver'] = new bsWidgetFormInputTypeAhead(array(
        'source' => cqContext::getInstance()->getController()->genUrl(array(
            'sf_route' => 'ajax_typeahead',
            'section' => 'messages',
            'page' => 'compose'
        )),
        'submit_on_enter' => false,
        'min_activation_chars' => 3,
      ));
    }

    $this->validatorSchema['receiver'] = new sfValidatorCallback(array(
        'required' => true,
        'callback' => array($this, 'validateReceiverField'),
    ));
  }

  /**
   * Validate the receiver field. Several values are permitted:
   *
   * 1) email address of a registered Collector, the model obeject is returned
   * 2) email address of a non-registered user, the plain email is returned
   * 3) a collector username, the Collector object is retuned
   * 4) a collector display name, the Colletor object is returned
   *
   * @param     sfValidatorBase  $validator
   * @param     string           $value
   * @param     array            $arguments
   *
   * @return    mixed Either a valid email for a user not registered
   *                  at the site or a Collector object
   */
  public function validateReceiverField(sfValidatorBase $validator, $value, $arguments = array())
  {
    // first we check if the value is a valid email address
    $is_email = $this->isReceiverAnEmailAddress($value);

    // if the value is a valid email
    if ($is_email)
    {
      // check if we have a user with that email, if yes return it,
      // if no return the plain email
      return CollectorQuery::create()->findOneByEmail($value)
        ?: $value;
    }

    // else validate with cqValidatorCollectorByName
    $v = new cqValidatorCollectorByName(
      array(
        'invalid_ids' => array($this->sender_collector->getId()),
        'return_object' => true,
      ),
      array(
        'collector_invalid_id' => 'You cannot send messages to yourself.'
      )
    );

    return $v->clean($value);
  }

  protected function setupSenderField()
  {
    $this->widgetSchema['sender'] = new sfWidgetFormInputHidden();
    // check if we are replying to a thread
    $this->validatorSchema['sender'] = new cqValidatorCollectorByName(array(
        'return_object' => true,
    ));
  }

  protected function setupThreadField()
  {
    $this->widgetSchema['thread'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['thread'] = new sfValidatorPropelChoice(array(
        'required' => false,
        'model'    => 'PrivateMessage',
        'column'   => 'thread',
    ));
  }

  protected function setupCaptchaField()
  {
    // setup captha only every $threshold sent messages
    if ($this->userSentMessagesCaptchaThresholdReached())
    {
      $this->widgetSchema['captcha'] = new cqWidgetBootstrapCaptcha(array(
          'width' => 200,
          'height' => 50,
      ));
      $this->validatorSchema['captcha'] = new IceValidatorCaptcha();
    }
  }

  protected function setupRedirectField()
  {
    $this->widgetSchema['goto'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['goto'] = new sfValidatorPass();
  }

  /**
   * We can attach a collection and/or a collectible to a message now.
   * Simply use the "attach" option to pass an object or an array
   */
  protected function setupAttachFields()
  {
    // setup all possible attached fields as hidden fields
    foreach (self::$attach_fields as $field => $model_class)
    {
      $this->widgetSchema[$field] = new sfWidgetFormInputHidden();
      $this->validatorSchema[$field] = new sfValidatorPropelChoice(array(
          'required' => false,
          'model' =>  $model_class,
          'column' => 'id',
      ));
    }

    // if we have an attach option passed to the form
    $object = $this->getOption('attach');

    // and it is a propel object
    if ($object instanceof BaseObject)
    {
      // and it is one of the allowed attach object types
      if (false !== $field = array_search(get_class($object), self::$attach_fields))
      {
        // add it's value to the appropriate hidden field
        $this->setDefault($field, $object->getPrimaryKey());
      }
    }
  }

  protected function setupCopyForSenderField()
  {
    $this->widgetSchema['copy_for_sender'] = new sfWidgetFormInputCheckbox();
    // we want to allow no value being passed to the field
    $this->validatorSchema['copy_for_sender'] = new sfValidatorPass();
  }

  /**
   * Returns the number of private messages sent by the user in this session
   *
   * @return integer
   */
  protected function userGetSentMessagesCount()
  {
    if ($this->sf_user)
    {
      return $this->sf_user->getSentCount(
        cqFrontendUser::SENT_COUNT_PRIVATE_MESSAGES
      );
    }

    return 0;
  }

  /**
   * Increments the session variable of how many private messages the user has sent
   */
  protected function userIncrementSentMessagesCount()
  {
    if ($this->sf_user)
    {
      $this->sf_user->incrementSentCount(
        cqFrontendUser::SENT_COUNT_PRIVATE_MESSAGES
      );
    }
  }

  /**
   * Check if we have reached the captcha threshold for sent messages
   *
   * @return    boolean
   */
  protected function userSentMessagesCaptchaThresholdReached()
  {
    $sent_messages = $this->userGetSentMessagesCount();
    $threshold = sfConfig::get('app_private_messages_require_captcha_threshold', 3);

    return (0 != $sent_messages) && (0 == $sent_messages % $threshold);
  }


  /**
   * Checking similarity of sent messages in one session
   *
   * If message similar to sent before we will send message about spam
   * else we will save for checking current message
   */
  protected function checkingMessagesSimilarity()
  {
    if ($this->sf_user)
    {
      $last_message = $this->sf_user->getAttribute(
        cqFrontendUser::PRIVATE_MESSAGES_SENT_TEXT, null, 'collector'
      );

      if ($last_message)
      {
        $percent = 0;
        similar_text($last_message, $this->getObject()->getBody(), $percent);

        if ($percent >= sfConfig::get('app_private_messages_similarity_percent'))
        {
          $this->sendSpamNotification($percent);
        }
      }

      $this->sf_user->setAttribute(
        cqFrontendUser::PRIVATE_MESSAGES_SENT_TEXT,
        $this->getObject()->getBody(),
        'collector'
      );
    }
  }

  protected function sendSpamNotification($percent)
  {
    $cqEmail = new cqEmail(cqContext::getInstance()->getMailer());

    $cqEmail->send('internal/spam_notification', array(
        'to' => sfConfig::get('app_private_messages_spam_receiver'),
        'subject' => 'Spam notification',
        'params' => array(
          'sender' => $this->getObject()->getCollectorRelatedBySenderId(),
          'receiver' => $this->getObject()->getCollectorRelatedByReceiverId(),
          'similarity' => $percent,
          'lastText' => $this->sf_user->getAttribute(
          cqFrontendUser::PRIVATE_MESSAGES_SENT_TEXT, null, 'collector'),
          'currentText' => $this->getObject()->getBody(),
        ),
    ));
  }

  /**
   * Update the object and handle set body's specifics
   *
   * @param     array $values
   */
  protected function doUpdateObject($values)
  {
    parent::doUpdateObject($values);

    $this->getObject()->setIsRich(false);
    $this->getObject()->setBody($values['body'], true);
    $this->getObject()->setSender($values['sender']);
    $this->getObject()->setReceiver($values['receiver']);

    // if we are sending a message to an email address
    if ($this->isReceiverAnEmailAddress($values['receiver']))
    {
      // then mark the message read, because there is no way to check that
      $this->getObject()->setIsRead(true);
    }

    // handle attached fields
    foreach (self::$attach_fields as $field => $model_class)
    {
      // if we have a value set for one of the fields
      if ($values[$field])
      {
        // then we set is as attached object
        $this->getObject()->setAttachedObjectData($model_class, $values[$field]);
      }
    }
  }

  /**
   * @param     string $receiver
   * @return    boolean
   */
  protected function isReceiverAnEmailAddress($receiver)
  {
    // if validation has passed, we might be getting an Collector object
    if ($receiver instanceof Collector)
    {
      return false;
    }

    // for a normal string, try to evaluate based on sfValidatorEmail
    try
    {
      $v = new sfValidatorEmail();
      $value = $v->clean((string) $receiver);

      return true;
    }
    catch (sfValidatorError $e)
    {
      return false;
    }
  }

  /**
   * perform the actual save, and if necessary reset sent messages count
   *
   * @param     PropelPDO $con
   */
  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->checkingMessagesSimilarity();
    $this->userIncrementSentMessagesCount();
  }

  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (null !== $this->thread)
    {
      $this->setDefault('thread', $this->thread);
      if (( $receiver = $this->getReceiverFromThread() ))
      {
        $this->setDefault('receiver', $receiver);
      }
    }

    $this->setDefault('sender', $this->sender_collector->getUsername());
  }

  /**
   *
   * @return string|null Username or an email address
   */
  protected function getReceiverFromThread()
  {
    if (null !== $this->thread)
    {
      $message = PrivateMessageQuery::create()
        ->filterByThread($this->thread)
        ->orderByCreatedAt(Criteria::ASC)
        ->findOne();

      $receiver = $this->sender_collector == $message->getCollectorRelatedByReceiverId()
        ? $message->getCollectorRelatedBySenderId()
        : $message->getCollectorRelatedByReceiverId();

      // when the receiver is not a valid obect (ie, it was an email address)
      // we get it directly from the message object, otherwize return the username
      return $receiver
        ? $receiver->getUsername()
        : $message->getReceiverEmail();
    }

    return null;
  }

  public function getDefaults()
  {
    return array_merge(array(
        'sender' => $this->sender_collector->getId(),
    ), parent::getDefaults());
  }

  public function unsetFields()
  {
    parent::unsetFields();

    unset ($this['id']);
    unset ($this['sender_id']);
    unset ($this['receiver_id']);
    unset ($this['receiver_email']);
    unset ($this['is_read']);
    unset ($this['is_rich']);
    unset ($this['is_replied']);
    unset ($this['is_forwarded']);
    unset ($this['is_marked']);
    unset ($this['is_deleted']);
    unset ($this['is_spam']);
    unset ($this['created_at']);
  }

}
