<?php

/**
 * A convenience form to compose a private message to a particular collector
 *
 * The only visible field to the user will be the Message Body
 */
class ComposeAbridgedPrivateMessageForm extends ComposePrivateMessageForm
{
  /** @var Collector|string */
  protected $receiver;

  /**
   * @param     Collector $sender
   * @param     Collector|string $receiver Either a collector or an email
   * @param     string $subject
   * @param     array $options
   * @param     string $CSRFSecret
   *
   * @see       ComposePrivateMessage.class.php
   */
  public function __construct(
    Collector $sender,
    $receiver,
    $subject,
    $options = array(),
    $CSRFSecret = null
  ) {
    $this->receiver = $receiver;

    parent::__construct($sender, $thread = null, $options, $CSRFSecret);

    $this->setDefault('subject', $subject);
  }


  public function configure()
  {
    parent::configure();

    $this->widgetSchema['receiver'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['subject'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['goto']->setAttribute('class', 'set-value-to-href');

    $this->widgetSchema['body']->setAttribute(
      'placeholder',
      'Send a message to '. $this->receiver
    );
  }

  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $this->setDefault('receiver', $this->receiver instanceof Collector
      ? $this->receiver->getUsername()
      : $this->receiver);
  }

  /**
   * Modify to be compatible with the target form :)
   */
  public function getCSRFToken($secret = null)
  {
    if (null === $secret)
    {
      $secret = $this->localCSRFSecret ? $this->localCSRFSecret : self::$CSRFSecret;
    }

    return md5($secret.session_id().'ComposePrivateMessageForm');
  }

}
