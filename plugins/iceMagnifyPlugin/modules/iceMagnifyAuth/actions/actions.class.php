<?php

/**
 * iceMagnifyAuth actions.
 *
 * @package    CollectorsQuest
 * @subpackage iceMagnifyAuth
 * @author     Collectors
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class iceMagnifyAuthActions extends sfActions
{

  /**
   * Action Validate
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeValidate(sfWebRequest $request)
  {
    $response = $this->getResponse();
    $request->setRequestFormat('xml');
    $this->setLayout(false);

    /* @var $collector Collector */
    $this->collector  = $this->getUser()->getCollector();
  }

  /**
   * Action Identity
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeIdentity(sfWebRequest $request)
  {
    $url       = 'http://' . sfConfig::get('app_magnify_channel') . '/login/sso'; //'http://video.collectorsquest.next/sso/validate';
    $secretKey = sfConfig::get('app_magnify_sso_secret_key', false);
    if (!$secretKey)
    {
      throw new Exception('Magnify SSO secret key is not set');
    }
    $params    = array(
      'user_id' => $this->getUser()->getId(),
      'ts'      => time(),
    );

    $signature = md5(http_build_query($params) . $secretKey);

    $redirectUrl = $url . '?' . http_build_query(array_merge($params, array('signature'=> $signature)));

    $this->redirect($redirectUrl);
  }

}
