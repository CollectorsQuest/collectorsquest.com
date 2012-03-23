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
   * @param     array $options
   *
   * @return    integer number of actually sent emails (recepients + cc + bcc)
   */
  public function send($name, $options = array())
  {
    $options = array_merge(
      array('params' => array()),
      cqEmailsConfig::getDataForName($name),
      $options);

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

    $rendered_template = $template->render($options['params']);

    $message = Swift_Message::newInstance()
      ->setFrom($options['from'])
      ->setTo($options['to'])
      ->setSubject($options['subject'])
      ->setCharset('UTF-8')
      ->addPart(strip_tags($rendered_template), 'text/plain')
      ->addPart($rendered_template, 'text/html');

    return $this->getMailer()->send($message);
  }

}
