<?php

class ajaxComponents extends cqComponents
{

  public function executeLoginForm()
  {
    $this->form = new CollectorLoginForm();
    $this->rpxnow = sfConfig::get('app_credentials_rpxnow');
  }

}