<?php

/**
 * @method  sfPropelRoute    getRoute()
 * @method  sfWebController  getController()
 * @method  cqWebResponse    getResponse()
 * @method  cqWebRequest     getRequest()
 * @method  cqUser           getUser()
 */
class cqActions extends sfActions
{
  /**
   * @return Collector
   */
  protected function getCollector()
  {
    return $this->getUser()->getCollector();
  }

  /**
   * @param  array  $helpers
   * @return void
   */
  protected function loadHelpers($helpers)
  {
    /** @var $configuration sfApplicationConfiguration */
    $configuration = sfProjectConfiguration::getActive();
    $configuration->loadHelpers($helpers);
  }

  /**
   * @param  string  $name
   * @param  string  $url
   * @param  array   $options
   *
   * @return void
   */
  protected function addBreadcrumb($name, $url = null, $options = array())
  {
    cqBreadcrumbs::getInstance()->addItem($name, $url, $options);
  }

  /**
   * @param  string  $title
   * @param  bool    $readonly
   *
   * @return void
   */
  public function prependTitle($title, $readonly = false)
  {
    if (sfConfig::get('app_title_readonly', false))
    {
      return;
    }
    sfConfig::set('app_title_readonly', $readonly);

    $response = $this->getResponse();
    $delimiter = sfConfig::get('app_title_delimiter', ' - ');
    $current_title = ($response->getTitle()) ? $response->getTitle() : sfConfig::get('app_title', 'CollectorsQuest.com');
    $response->setTitle($title . $delimiter . $current_title, false);
  }

  /**
   * @return string
   */
  protected function getCulture()
  {
    return $this->getUser()->getCulture();
  }

  /**
   * @param  string   $internal_uri
   * @param  boolean  $absolute
   *
   * @return string
   */
  protected function getUrlFor($internal_uri, $absolute = false)
  {
    return $this->getController()->genUrl($internal_uri, $absolute);
  }

  /**
   * @param  string  $to
   * @param  string  $subject
   * @param  string  $body
   *
   * @return false|int
   */
  protected function sendEmail($to, $subject, $body)
  {
    $message = $this->getMailer()->compose('no-reply@collectorsquest.com', $to, $subject);
    $message->setFrom('no-reply@collectorsquest.com', 'CollectorsQuest.com');
    $message->setReplyTo('info@collectorsquest.com');
    $message->setCharset('UTF-8');
    $message->addPart(strip_tags($body), 'text/plain');
    $message->addPart($body, 'text/html');

    // Actually send the email
    return $this->getMailer()->send($message);
  }

  /**
   * @param  string  $string
   * @param  array   $args
   * @param  string  $catalogue
   *
   * @return string
   */
  protected function __($string, $args = array(), $catalogue = 'messages')
  {
    return $this->getContext()->getI18n()->__($string, $args, $catalogue);
  }
}
