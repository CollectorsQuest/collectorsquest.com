<?php

/**
 * cqMail
 *
 * @package    Collectorsquest
 * @author     Prakash Panchal
 */
class cqMail
{
  /**
   * Function for replace  mail content.
   *
   * @access  public
   * @param   array   $asContent array of old content.
   * @param   array   $asDynamicContent array of dynamic content.
   *
   * @return  mixed   array|boolean array of mail content, or false if parameter is null
   */
  private $template = '';

  public function setTemplate($template_name)
  {
    $this->template = $template_name;
  }

  public function replaceMailContent($replacements)
  {
    if (!$message = @file_get_contents(realpath(dirname(__FILE__)) . "/../templates/emails/" . $this->template))
    {
      return false;
    }

    if (is_array($replacements) && !empty($replacements))
    {
      foreach ($replacements as $key => $value)
      {
        $message = str_replace($key, $value, $message);
      }
    }

    $message = file_get_contents(realpath(dirname(__FILE__)) . "/../templates/emails/header.html") . $message;
    $message = $message . file_get_contents(realpath(dirname(__FILE__)) . "/../templates/emails/footer.html");

    return $message;
  }

  public function sendMail($ssMailTo, $ssMailFrom, $ssMailSubject, $ssMailBody)
  {
    $oMail = sfContext::getInstance()->getMailer()->compose();
    $oMail->setSubject($ssMailSubject);
    $oMail->setTo(trim($ssMailTo));
    $oMail->setFrom($ssMailFrom);
    $oMail->setBody($ssMailBody, 'text/html');

    return sfContext::getInstance()->getMailer()->send($oMail);
  }
}
