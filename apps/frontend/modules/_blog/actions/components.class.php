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

    if ($this->data['is_single'] === true || $this->data['is_page'] === true)
    {
      $this->wp_post = wpPostQuery::create()->findOneById($this->data['the_id']);
    }
    else if ($this->data['is_author'] === true)
    {
      $this->wp_user = wpUserQuery::create()->findOneById($this->data['the_id']);
    }
  }
}
