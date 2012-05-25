<?php

/**
 * Description of ComposePrivateMessageForm
 */
class ComposePrivateMessageForm extends PrivateMessageForm
{

  /** @var Collector */
  protected $sender_collector;

  /** @var string */
  protected $thread;

  /**
   * The Compose form takes a Collector object in its constructor that
   * will be set the "sender"
   *
   * If $sf_user parameter is supplied, the for will check how many messages
   * has the user sent in the current session and append a captcha field
   * if over the threshold
   *
   * @param     Collector $sender
   * @param     sfUser $sf_user
   * @param     string $thread
   * @param     arrray $options
   * @param     string $CSRFSecret
   */
  public function __construct(
    Collector $sender,
    sfUser $sf_user = null,
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
    $this->setupThreadField();
    $this->setupCaptchaField();

    $this->widgetSchema->setLabels(array(
        'receiver' => 'To',
        'body' => 'Message',
    ));

    $this->unsetFields();
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
        'source' => sfContext::getInstance()->getController()->genUrl(array('sf_route' => 'ajax_typeahead', 'section' => 'messages', 'page' => 'compose'))
      ));
    }

    $this->validatorSchema['receiver'] = new cqValidatorCollectorByName(array(
        'invalid_ids' => array($this->sender_collector->getId()),
        'return_object' => true,
      ), array(
        'collector_invalid_id' => 'You cannot send messages to yourself.'
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
      $this->widgetSchema['captcha'] = new IceWidgetCaptcha(array(
          'width' => 200,
          'height' => 50,
      ));
      $this->validatorSchema['captcha'] = new IceValidatorCaptcha();
    }
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
        0,
        'collector'
      );
    }
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
  }

  /**
   * perform the actual save, and if necessary reset sent messages count
   *
   * @param type $con
   */
  protected function doSave($con = null)
  {
    parent::doSave($con);

    if ( $this->userGetSentMessagesCount()
      < sfConfig::get('app_private_messages_require_captcha_threshold') )
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
    return array_merge(array('sender' => $this->sender_collector->getId(),), parent::getDefaults());
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
