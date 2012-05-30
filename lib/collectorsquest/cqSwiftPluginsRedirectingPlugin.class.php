<?php

/**
 * Redirects all email to a single recipient, if callback condition met
 *
 * @see     Swift_Plugins_RedirectingPlugin
 */
class cqSwiftPluginsRedirectingPlugin extends Swift_Plugins_RedirectingPlugin
{

  protected $callback;

  /**
   * Create a new RedirectingPlugin.
   *
   * @param     int $recipient
   * @param     Callable
   */
  public function __construct($recipient, $callback)
  {
    $this->callback = $callback;

    parent::__construct($recipient);
  }

  /**
   * Invoked immediately before the Message is sent.
   * @param Swift_Events_SendEvent $evt
   */
  public function beforeSendPerformed(Swift_Events_SendEvent $evt)
  {
    $message = $evt->getMessage();

    if (call_user_func($this->callback, $message))
    {
      parent::beforeSendPerformed($evt);
    }
  }

}
