<?php

class _videoActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header', 'video');
  }

  public function executeIndex()
  {
    $this->redirect('http://'. sfConfig::get('app_magnify_channel', 'video.collectorsquest.com'), 301);
  }

  public function executeHeader()
  {
    sfConfig::set('sf_web_debug', false);

    return sfView::SUCCESS;
  }

  public function executeFooter()
  {
    sfConfig::set('sf_web_debug', false);

    return sfView::SUCCESS;
  }
}
