<?php

class _blogComponents extends cqFrontendComponents
{
  public function executeSidebar()
  {
    $key = $this->getRequestParameter('key');

    if (function_exists('xcache_get'))
    {
      $this->data = xcache_get($key);
    }
    else
    {
      $this->data = zend_shm_cache_fetch($key);
    }
  }
}
