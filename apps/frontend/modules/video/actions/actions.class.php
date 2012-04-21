<?php

class videoActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $this->redirect('http://'. sfConfig::get('app_magnify_channel', 'video.collectorsquest.com'), 301);
  }
}
