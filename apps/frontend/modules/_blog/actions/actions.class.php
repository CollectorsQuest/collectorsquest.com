<?php

class _blogActions extends cqFrontendActions
{

  public function preExecute()
  {
    $this->getResponse()->addMeta('title', null, true);
    $this->getResponse()->addMeta('description', null, true);
    $this->getResponse()->addMeta('keywords', null, true);

    SmartMenu::setSelected('header', 'blog');
  }

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

    if (!empty($this->data['breadcrumbs']) && is_array($this->data['breadcrumbs']))
    {
      foreach ($this->data['breadcrumbs'] as $breadcrumb)
      {
        $this->addBreadcrumb($breadcrumb['name'], @$breadcrumb['url']);
      }
    }
    else if (!$this->data['is_page'])
    {
      $this->addBreadcrumb('Blog', 'blog/index');
    }

    // This will make sure the 'Blog' from above is a link
    if (!empty($this->data['breadcrumbs'])) {
      $this->addBreadcrumb('', null);
    }

    // We do not want to highlight the Blog header menu on static pages
    if ($this->data['is_page'])
    {
      SmartMenu::setSelected('header', null);
    }

    // Set the right title based on data from the blog
    $this->getResponse()->setTitle($this->data['title']);

    return sfView::SUCCESS;
  }
}
