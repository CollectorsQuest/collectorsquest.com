<?php

class _ajaxComponents extends cqFrontendComponents
{
  public function executeModalLogin()
  {
    $this->login_form = new CollectorLoginForm();
    $this->signup_form = new CollectorSignupStep1Form();
    $this->rpxnow = sfConfig::get('app_credentials_rpxnow');
  }

  public function executeSocialModalLogin()
  {

  }

}
