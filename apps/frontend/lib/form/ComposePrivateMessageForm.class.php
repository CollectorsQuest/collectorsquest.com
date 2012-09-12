<?php

/**
 * ComposePrivateMessageForm does what its name says :)
 *
 * Options:
 *    attach: Collection/Collectible object (or an array of both)
 *
 */
class ComposePrivateMessageForm extends PrivateMessageForm
{

  /** @var cqBaseUser */
  protected $sf_user;

  /** @var Collector */
  protected $sender_collector;

  /** @var string */
  protected $thread;

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
    cqBaseUser $sf_user = null,
    $thread = null,
    $options = array(),
    $CSRFSecret = null
  ) {
    $this->sender_collector = $sender;
    $this->thread = $thread;
    $this->sf_user = $sf_user;

    parent::__construct(null, $options, $CSRFSecret);
  }

  public function configure()
  {
    parent::configure();

    $this->setupReceiverField();
    $this->setupSenderField();
    $this->setupThreadField();
    $this->setupCaptchaField();
    $this->setupRedirectField();
    $this->setupAttachFields();
    $this->setupCopyForSenderField();
    $this->setupIpAddressField();

    $this->widgetSchema->setLabels(array(
        'receiver' => 'To',
        'body' => 'Message',
        'copy_for_sender' => 'Email me a copy',
    ));

    $this->unsetFields();

    $this->mergePostValidator(new iceSpamControlValidatorSchema(array(
        'credentials' => iceSpamControl::CREDENTIALS_ALL,
        'fields' => array(
            $this->getIpAddressFieldName() => 'ip',
        ),
      ), array(
        'spam' => 'We are sorry we could not send your private message. Please try again later.',
    )));
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
        'source' => cqContext::getInstance()->getController()->genUrl(
          array('sf_route' => 'ajax_typeahead', 'section' => 'messages', 'page' => 'compose')
        )
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
    try
    {
      $v = new sfValidatorEmail();
      $value = $v->clean($value);
      $is_email = true;
    }
    catch (sfValidatorError $e)
    {
      $is_email = false;
    }

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
    if ( $this->userGetSentMessagesCount()
      >= sfConfig::get('app_private_messages_require_captcha_threshold') )
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
    $this->widgetSchema['collection_id'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['collection_id'] = new sfValidatorPropelChoice(array(
        'required' => false,
        'model' =>  'Collection',
        'column' => 'id',
    ));

    $this->widgetSchema['collectible_id'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['collectible_id'] = new sfValidatorPropelChoice(array(
        'required' => false,
        'model' =>  'Collectible',
        'column' => 'id',
    ));

    // if we have an attach option passed to the form
    if ($this->getOption('attach'))
    {
      // try to set defaults for attached collection/collectible
      foreach ((array) $this->getOption('attach') as $object)
      {
        if ($object instanceof Collection)
        {
          $this->setDefault('collection_id', $object->getPrimaryKey());
        }

        if ($object instanceof Collectible)
        {
          $this->setDefault('collectible_id', $object->getPrimaryKey());
        }
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
      return $this->sf_user->getAttribute(
        cqFrontendUser::PRIVATE_MESSAGES_SENT_COUNT_KEY, 0, 'collector'
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
      $this->sf_user->setAttribute(
        cqFrontendUser::PRIVATE_MESSAGES_SENT_COUNT_KEY,
        $this->sf_user->getAttribute(
          cqFrontendUser::PRIVATE_MESSAGES_SENT_COUNT_KEY, 0, 'collector') + 1,
        'collector'
      );
    }
  }

  /**
   * Reset sent messages count back to 0. We do not want to display captcha
   * on every message after the threshold, only on every (threshold number) messages
   *
   * If the user solves it right one time they should not be required to do it again
   * for a while
   */
  protected function userResetSentMessagesCount()
  {
    if ($this->sf_user)
    {
      $this->sf_user->setAttribute(
        cqFrontendUser::PRIVATE_MESSAGES_SENT_COUNT_KEY,
        0, 'collector'
      );
    }
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
          'sender' => $this->getObject()->getCollectorRelatedBySender(),
          'receiver' => $this->getObject()->getCollectorRelatedByReceiver(),
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

    if ($values['collectible_id'])
    {
      $this->getObject()->setAttachedCollectibleId($values['collectible_id']);
    }

    if ($values['collection_id'])
    {
      $this->getObject()->setAttachedCollectionId($values['collection_id']);
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

    // When do we show the captcha?
    $threshold = sfConfig::get('app_private_messages_require_captcha_threshold', 5);

    $this->checkingMessagesSimilarity();

    if ($this->userGetSentMessagesCount() < $threshold)
    {
      // we have not reached the threshold yet, so incriment the count
      $this->userIncrementSentMessagesCount();
    }
    else
    {
      // we have reached the threshold, and the user successfully solved the captha
      // so we reset the count to 0
      $this->userResetSentMessagesCount();
    }
  }

  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (null !== $this->thread)
    {
      $this->setDefault('thread', $this->thread);
      if (( $receiver = $this->getReceiverFromThread() ))
      {
        $this->setDefault('receiver', $receiver->getUsername());
      }
    }

    $this->setDefault('sender', $this->sender_collector->getUsername());
  }

  protected function getReceiverFromThread()
  {
    if (null !== $this->thread)
    {
      $message = PrivateMessageQuery::create()
        ->filterByThread($this->thread)
        ->findOne();

      return $this->sender_collector == $message->getCollectorRelatedByReceiver()
        ? $message->getCollectorRelatedBySender()
        : $message->getCollectorRelatedByReceiver();
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
    unset ($this['is_read']);
    unset ($this['is_rich']);
    unset ($this['is_replied']);
    unset ($this['is_forwarded']);
    unset ($this['is_marked']);
    unset ($this['is_deleted']);
    unset ($this['created_at']);
  }

}
