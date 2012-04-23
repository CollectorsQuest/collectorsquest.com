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
   * @param     Collector $sender
   * @param     arrray $options
   * @param     string $CSRFSecret
   */
  public function __construct(Collector $sender, $thread = null, $options = array(), $CSRFSecret = null)
  {
    $this->sender_collector = $sender;
    $this->thread = $thread;

    parent::__construct(null, $options, $CSRFSecret);
  }

  public function configure()
  {
    parent::configure();

    $this->setupReceiverField();
    $this->setupThreadField();

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
      $this->widgetSchema['receiver'] = new sfWidgetFormInputText();
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
    $this->validatorSchema['thread'] = new sfValidatorPass();
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
