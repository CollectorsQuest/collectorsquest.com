<?php

class _calendarActions extends cqActions
{
  public function preExecute()
  {
    $this->addBreadcrumb($this->__('Events'), '/calendar/events/index.php');
    $this->prependTitle($this->__('Events'));
  }

  public function executeIndex(sfWebRequest $request)
  {
    $key = $request->getParameter('key');

    if (function_exists('xcache_get'))
    {
      $data = xcache_get($key);
    }
    else
    {
      $data = zend_shm_cache_fetch($key);
    }

    $this->getResponse()->addMeta('keywords', $data['meta_keywords'], true);
    $this->getResponse()->addMeta('description', $data['meta_description'], true);

    return sfView::SUCCESS;
  }
}