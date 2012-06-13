<?php

/**
 * cqMagnifyAuth actions.
 *
 * @package    CollectorsQuest
 * @subpackage cqMagnifyAuth
 * @author     Collectors
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cqMagnifyAuthActions extends sfActions
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
    // Turn off web debug
    sfConfig::set('sf_web_debug', false);

    $q = CollectorQuery::create()
       ->filterByExtraProperty('magnify.sso_signature', $request->getParameter('signature'));
    $collector = $q->findOne();

    /**
     * Check if there is a Collector with that signature and
     * if the timestamp is not too much in the past (1 hour)
     */
    if (!$collector || (int)$collector->getProperty('magnify.sso_timestamp') < time() - 3600)
    {
      return sfView::NONE;
    }

    switch ($request->getParameter('method'))
    {
      case 'getUserInfo':
      default:
        $request->setRequestFormat('xml');
        $this->collector = $collector;

        $this->setTemplate('userInfo');
        return sfView::SUCCESS;
        break;
    }

    return sfView::NONE;
  }

  /**
   * Action Identity
   *
   * @throws Exception
   * @return void
   */
  public function executeIdentity()
  {
    $redirectUrl = $this->getController()->genUrl('@cq_magnify_sso_identity', true);

    $this->redirectUnless($this->getUser()->isAuthenticated(), '@login?r=' . $redirectUrl);

    /** @var $collector Collector */
    $collector = $this->getUser()->getCollector();

    $url       = 'http://' . sfConfig::get('app_magnify_channel') . '/login/sso';
    $secretKey = sfConfig::get('app_magnify_sso_secret_key', null);
    $timestamp = time();

    if (!$secretKey)
    {
      throw new Exception('Magnify SSO secret key is not set');
    }

    $params = array(
      'user_id'   => $collector->getId(),
      'timestamp' => $timestamp,
    );

    $signature = md5(http_build_query($params) . $secretKey);

    $collector->setProperty('magnify.sso_signature', $signature);
    $collector->setProperty('magnify.sso_timestamp', $timestamp);
    $collector->save();

    $redirectUrl = $url . '?' . http_build_query(array_merge($params, array('signature' => $signature)));

    $this->redirect($redirectUrl);
  }

}
