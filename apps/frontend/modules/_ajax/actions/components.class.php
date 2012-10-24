<?php

class _ajaxComponents extends cqFrontendComponents
{
  public function executeModalLogin()
  {
    $this->login_form = new CollectorLoginForm();
  }

  public function executeSocialModalLogin()
  {

  }

}
