<?php

/**
 * cqEmail for reasy sending of pre-defined twig email templates
 */
class cqEmail
{
  protected $mailer;

  protected $mandatory_params = array(
      'from',
      'to',
      'subject',
  );

  /**
   * @param Swift_Mailer $mailer
   */
  public function __construct(Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  /**
   * @return    Swift_Mailer
   */
  public function getMailer()
  {
    return $this->mailer;
  }

  /**
   * @param     string $name the name of the pre-defined email template
   * @param     array  $options
   *
   * @throws    InvalidArgumentException
   * @throws    Swift_TransportException
   *
   * @return    integer number of actually sent emails (recepients + cc + bcc)
   */
  public function send($name, $options = array())
  {
    $options = array_merge(
      array('params' => array()),
      cqEmailsConfig::getDataForName($name),
      $options
    );

    $missing_params = array_diff($this->mandatory_params, array_keys($options));
    if (!empty($missing_params))
    {
      throw new InvalidArgumentException(sprintf(
        'cqEmail::send() has one or more missing mandatory options: [%s]',
        implode(', ', $missing_params)
      ));
    }

    if (!$name instanceof cqEmailTemplate)
    {
      $template = new cqEmailTemplate($name, $options);
    }
    else
    {
      $template = $name;
    }

    // Render the subject, while replacing any varibles
    $rendered_subject = cqEmailsConfig::getTwigStringEnvironment()
      ->render($options['subject'], $options['params']);

    // We need to make the subject available to the template
    $options['params']['subject'] = $rendered_subject;

    // Render the body of the email
    $rendered_template = $template->render($options['params']);

    try
    {
      $message = Swift_Message::newInstance()
        ->setFrom($options['from'])
        ->setReplyTo(isset($options['replyTo']) ? $options['replyTo'] : $options['from'])
        ->setTo($options['to'])
        ->setCc(isset($options['cc']) && $options['cc'] ? $options['cc'] : array())
        ->setSubject($rendered_subject)
        ->setCharset('UTF-8')
        ->addPart(strip_tags($rendered_template), 'text/plain')
        ->addPart($rendered_template, 'text/html');

      $return = $this->getMailer()->send($message);
    }
    catch (Swift_RfcComplianceException $e)
    {
      // one of the emails failed swift address validation, in which case simply
      // assume that the message is undelivarable and return false

      return false;
    }
    catch (Swift_TransportException $e)
    {
      // Handle error 554 from Amazon SES described here:
      // https://forums.aws.amazon.com/thread.jspa?threadID=91218
      if (false !== stripos($e->getMessage(), '554 Message rejected: Address blacklisted'))
      {
        $return = false;
      }
      else
      {
        throw $e;
      }
    }

    return $return;
  }

}
