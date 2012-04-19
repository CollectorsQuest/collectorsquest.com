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
    $this->setLayout(false);

    /* @var $collector Collector */
    $collector  = $this->getUser()->getCollector();
    $multimedia = $collector->getPhoto();
    $which      = '100x100';
    if (!$multimedia->fileExists($which))
    {
      $src = sprintf(
        '%simages/legacy/multimedia/%s/%s.png',
        sfConfig::get('app_cq_www_domain'),
        $multimedia->getModel(),
        $which
      );
    }
    else
    {
      $src = sprintf(
        '%s/%s/%s/%s-%d.%s?%d',
        sfConfig::get('app_cq_multimedia_domain'),
        $multimedia->getType(), $which,
        (!empty($options['slug'])) ? $options['slug'] : strtolower($multimedia->getModel()),
        $multimedia->getId(), $multimedia->getFileExtension(), $multimedia->getUpdatedAt('U')
      );
    }

    /* @var $response cqWebResponse */
    $response->setHttpHeader('Expires', 0);
    $response->setHttpHeader('Cache-control', 'private');
    $response->setHttpHeader('Cache-Control', 'private, must-revalidate, post-check=0, pre-check=0');
    $response->setHttpHeader('Content-Type', 'application/xml, charset=UTF-8; encoding=UTF-8');

    $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<userinfo>
  <id>{$collector->getId()}</id>
  <handle>{$collector->getUsername()}</handle>
  <email>{$collector->getEmail()}</email>
  <name>{$collector->getDisplayName()}</name>
  <photo>{$src}</photo>
</userinfo>
XML;

//    return $this->renderText($xml);
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
