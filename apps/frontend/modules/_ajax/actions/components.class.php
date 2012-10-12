<?php

class _ajaxComponents extends cqFrontendComponents
{
  public function executeModalLogin()
  {
    $this->login_form = new CollectorLoginForm();

    return sfView::SUCCESS;
  }

  public function executeSocialModalLogin()
  {
    return sfView::SUCCESS;
  }

}
