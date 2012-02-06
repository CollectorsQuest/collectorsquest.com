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
}
