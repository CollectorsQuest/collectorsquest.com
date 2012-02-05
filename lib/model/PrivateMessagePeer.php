<?php

require 'lib/model/om/BasePrivateMessagePeer.php';

class PrivateMessagePeer extends BasePrivateMessagePeer
{
  /**
   * Generates a random string (32 cahrs) for use as thread id in PrivateMessages
   *
   * @return string
   */
  public static function generateThread()
  {
    return md5(rand(1, 99999).uniqid());
  }

  /**
   * Send a PrivateMessage to the $receiver
   *
   * @param  int | Collector  $receiver
   * @param  int | Collector  $sender
   * @param  array            $options
   *
   * @return PrivateMessage
   */
  public static function send($receiver, $sender = 1, $options = array())
  {
    $thread  = !empty($options['thread'])  ? $options['thread']  : self::generateThread();
    $subject = !empty($options['subject']) ? $options['subject'] : '(no subject)';
    $body    = !empty($options['body'])    ? $options['body']    : null;

    $message = new PrivateMessage();
    $message->setThread($thread);
    $message->setSender($sender);
    $message->setReceiver($receiver);
    $message->setSubject($subject);

    // It is important to set the is_rich before setting the body because it depends on that option
    $message->setIsRich(isset($options['rich']) ? $options['rich'] : false);
    $message->setBody($body, isset($options['clean']) ? $options['clean'] : true);

    return $message->save() ? $message : null;
  }

  /**
   * Send a PrivateMessage to the $receiver by taking the subject and body from the MessageTemplate with $template_id
   *
   * @param  integer|MessageTemplate  $template
   * @param  integer|Collector        $receiver
   * @param  integer|Collector        $sender
   * @param  array  $options
   *
   * @see PrivateMessagePeer::send()
   *
   * @return PrivateMessage
   */
  public static function sendFromTemplate($template, $receiver, $sender = 1, $options = array())
  {
    if (is_numeric($template))
    {
      $template = PrivateMessageTemplatePeer::retrieveByPk($template);
    }
    if (!$template instanceof PrivateMessageTemplate)
    {
      return false;
    }

    $subject = isset($options['strtr']) ? strtr($template->getSubject(), $options['strtr']) : $template->getSubject();
    $body = isset($options['strtr']) ? strtr($template->getBody(), $options['strtr']) : $template->getBody();

    // We need to make sure not special tags are left even if we could not replace them above
    // $subject = preg_replace('/({[\w\.]+})/iu', '', $subject);
    // $body = preg_replace('/({[\w\.]+})/iu', '', $body);

    $options = array(
      'subject' => $subject, 'body' => $body,
      'rich' => true, 'clean' => false
    );

    return self::send($receiver, $sender, $options);
  }
}
