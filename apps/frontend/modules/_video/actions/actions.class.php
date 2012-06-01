<?php

class _videoActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $this->redirect('http://'. sfConfig::get('app_magnify_channel', 'video.collectorsquest.com'), 301);
  }

  public function executeHeader()
  {
    if (sfConfig::get('sf_environment') !== 'prod')
    {
      $this->redirect('http://www.collectorsquest.com/_video/header', 302);
    }

    sfConfig::set('sf_web_debug', false);

    return sfView::SUCCESS;
  }

  public function executeFooter()
  {
    if (sfConfig::get('sf_environment') !== 'prod')
    {
      $this->redirect('http://www.collectorsquest.com/_video/footer', 302);
    }

    sfConfig::set('sf_web_debug', false);

    return sfView::SUCCESS;
  }
}
