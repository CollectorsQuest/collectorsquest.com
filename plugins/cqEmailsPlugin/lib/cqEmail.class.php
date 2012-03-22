<?php

/**
 * Description of cqEmail
 *
 * @author      Ivan Plamenov Tanev aka Crafty_Shadow @ WEBWORLD.BG <vankata.t@gmail.com>
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
   * @param     string $template
   * @param     array $options
   */
  public function send($template, $options = array())
  {
    $options = array_merge(
      array('params' => array()),
      cqEmailsConfig::getDataForName($template),
      $options);

    $missing_params = array_diff($this->mandatory_params, array_keys($options));
    if (!empty($missing_params))
    {
      throw new InvalidArgumentException(sprintf(
        'cqEmail::send() has one or more missing mandatory options: [%s]',
        implode(', ', $missing_params)
      ));
    }

    if (!$template instanceof cqEmailTemplate)
    {
      $template = new cqEmailTemplate($template, $options);
    }

    $body = $template->render($options['params']);

    $message = Swift_Message::newInstance()
      ->setFrom($options['from'])
      ->setTo($options['to'])
      ->setSubject($options['subject'])
      ->setCharset('UTF-8')
      ->addPart(strip_tags($body), 'text/plain')
      ->addPart($body, 'text/html');

    $this->getMailer()->send($message);
  }

}
