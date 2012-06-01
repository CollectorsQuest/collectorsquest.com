<?php

class _videoActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $this->redirect('http://'. sfConfig::get('app_magnify_channel', 'video.collectorsquest.com'), 301);
  }

  public function executeHeader()
  {
    sfConfig::set('sf_web_debug', false);

    return (sfConfig::get('sf_environment') === 'next') ? 'Iframe' : sfView::SUCCESS;
  }

  public function executeFooter()
  {
    sfConfig::set('sf_web_debug', false);

    return (sfConfig::get('sf_environment') === 'next') ? 'Iframe' : sfView::SUCCESS;
  }
}
