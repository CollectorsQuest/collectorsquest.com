<?php

/**
 * @method  cqPropelRoute    getRoute()
 * @method  sfWebController  getController()
 * @method  cqWebResponse    getResponse()
 * @method  cqWebRequest     getRequest()
 * @method  cqBaseUser       getUser()
 *
 * @method  mixed  redirect($url, $statusCode = 302)
 */
abstract class cqBaseAction extends sfAction
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
    IceBreadcrumbs::getInstance($this->getContext())->addItem($name, $url, $options);
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
   * @param  string   $internal_uri
   * @param  boolean  $absolute
   *
   * @return string
   */
  protected function getUrlFor($internal_uri, $absolute = false)
  {
    return $this->getController()->genUrl($internal_uri, $absolute);
  }

}
