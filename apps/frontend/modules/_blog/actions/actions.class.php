<?php

class _blogActions extends cqFrontendActions
{
  public function executeIndex()
  {
    // We do not want the web debug bar on blog requests
    sfConfig::set('sf_web_debug', false);

    $key = $this->getRequestParameter('key');

    if (function_exists('xcache_get'))
    {
      $this->data = xcache_get($key);
    }
    else
    {
      $this->data = zend_shm_cache_fetch($key);
    }

    $this->getResponse()->setTitle($this->data['title']);

    return sfView::SUCCESS;
  }
}
