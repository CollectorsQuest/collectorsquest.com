<?php

class ComposeAbridgedPrivateMessageForm extends ComposePrivateMessageForm
{
  /** @var Collector */
  protected $receiver_collector;

  public function __construct(Collector $sender, Collector $receiver, $subject, $options = array(), $CSRFSecret = null)
  {
    $this->receiver_collector = $receiver;

    parent::__construct($sender, $sf_user = null, $thread = null, $options, $CSRFSecret);

    $this->setDefault('subject', $subject);
  }


  public function configure()
  {
    parent::configure();

    $this->widgetSchema['receiver'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['subject'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['goto']->setAttribute('class', 'set-value-to-href');
  }

  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $this->setDefault('receiver', $this->receiver_collector->getUsername());
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