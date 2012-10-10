<?php

class cqBackendUser extends IceBackendUser
{
  public function beginAuthentication($trust_url, $return_url = 'http://backend.collectorsquest.com/openid')
  {
    return parent::beginAuthentication($trust_url, $return_url);
  }

  public function completeAuthentication($return_to = 'http://backend.collectorsquest.com/openid')
  {
    return parent::completeAuthentication($return_to);
  }

  public function getCookieUuid()
  {
    /** @var $request cqWebRequest */
    $request = cqContext::getInstance()->getRequest();

    return $request->getCookie('cq_uuid', null);
  }

  public function setAuthenticated($authenticated)
  {
    if ((bool) $authenticated !== $this->isAuthenticated())
    {
      /** @var $options array */
      $options = $this->getOptions();
      $cq_frontend_admin_cookie = sfConfig::get('app_frontend_admin_cookie_name', 'cq_admin');

      if ($authenticated === true)
      {
        sfContext::getInstance()->getResponse()->setCookie(
          $cq_frontend_admin_cookie,
          hash_hmac('sha1', $_SERVER['REMOTE_ADDR'], $this->getCookieUuid()),
          time() + $options['timeout'],
          '/',
          '.'. sfConfig::get('app_domain_name')
        );
        sfContext::getInstance()->getResponse()->setCookie(
          'cq_bc',
          $this->getGuardUser()->getId(),
          time() + $options['timeout'],
          '/',
          '.'. sfConfig::get('app_domain_name')
        );
      }
      else
      {
        sfContext::getInstance()->getResponse()->setCookie(
          $cq_frontend_admin_cookie, '', time() - $options['timeout'],
          '/',
          '.'. sfConfig::get('app_domain_name')
        );
        sfContext::getInstance()->getResponse()->setCookie(
          'cq_bc', '', time() - $options['timeout'],
          '/',
          '.'. sfConfig::get('app_domain_name')
        );
      }

    }

    return parent::setAuthenticated($authenticated);
  }
}
