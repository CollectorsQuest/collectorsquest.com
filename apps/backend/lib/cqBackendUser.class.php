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
    /* @var $request cqWebRequest */
    $request = cqContext::getInstance()->getRequest();

    return $request->getCookie('cq_uuid', null);
  }

  public function setAuthenticated($authenticated)
  {
    if ((bool) $authenticated !== $this->isAuthenticated())
    {
      /* @var $response cqWebResponse */
      $response = cqContext::getInstance()->getResponse();

      /* @var $options array */
      $options = $this->getOptions();

      /* @var $cq_admin_cookie string */
      $cq_admin_cookie = sfConfig::get('app_frontend_admin_cookie_name', 'cq_admin');

      if ($authenticated === true)
      {
        $hmac = hash_hmac(
          'sha1', $this->getGuardUser()->getId() .':'. $_SERVER['REMOTE_ADDR'], $this->getCookieUuid()
        );

        $response->setCookie(
          $cq_admin_cookie,
          $this->getGuardUser()->getId() .':'. $hmac,
          time() + $options['timeout'],
          '/',
          '.'. sfConfig::get('app_domain_name')
        );
      }
      else
      {
        $response->setCookie(
          $cq_admin_cookie, '', time() - $options['timeout'],
          '/',
          '.'. sfConfig::get('app_domain_name')
        );
      }
    }

    parent::setAuthenticated($authenticated);
  }
}
