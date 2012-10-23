<?php

/**
 * cqEmailLog save sent emails to db
 */
class cqEmailLog implements Swift_Events_SendListener
{

  public function sendPerformed(Swift_Events_SendEvent $e)
  {
    /* @var $message Swift_Mime_Message */
    $message =  $e->getMessage();

    /* @var $children Swift_Mime_MimeEntity[] */
    $children = $message->getChildren();
    $text_plain = '';
    $text_html = '';
    foreach ($children as $child)
    {
      switch ($child->getContentType())
      {
        case 'text/plain': $text_plain = $child->getBody(); break;
        case 'text/html':  $text_html = $child->getBody(); break;
      }
    }
    /* @var $from string[] */
    $from = $message->getFrom();

    $result = '';
    switch ($e->getResult())
    {
      case Swift_Events_SendEvent::RESULT_FAILED:    $result = 'failed'; break;
      case Swift_Events_SendEvent::RESULT_PENDING:   $result = 'pending'; break;
      case Swift_Events_SendEvent::RESULT_TENTATIVE: $result = 'tentative'; break;
      case Swift_Events_SendEvent::RESULT_SUCCESS:   $result = 'success'; break;
    }

    /* @var $headers Swift_Mime_SimpleHeaderSet */
    $headers = $message->getHeaders();

    //save each receivers as single email record
    foreach ($message->getTo() as $email => $receiver)
    {
      $sent_email = new EmailsLog();
      $sent_email
          ->setBody($message->getBody())
          ->setTextHtml($text_html)
          ->setTextPlain($text_plain)
          ->setSenderName(current($from))
          ->setSenderEmail(key($from))
          ->setSubject($message->getSubject())
          ->setReceiverEmail($email)
          ->setReceiverName($receiver)
          ->setResult($result)
          ->setDate($message->getDate())
          ->setHeaders($headers->toString())
          ->save();
     }
  }

  public function beforeSendPerformed(Swift_Events_SendEvent $e)
  {
  }

}
