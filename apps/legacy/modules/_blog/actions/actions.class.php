<?php

class _blogActions extends cqActions
{
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

    if ($data['is_page'])
    {
      $this->prependTitle($this->__('Pages'));
      $this->addBreadcrumb($this->__('Pages'));
    }
    else
    {
      $this->addBreadcrumb($this->__('Blog'), '/blog/index.php');
      $this->prependTitle($this->__('Blog'));
    }
    
    if ($data['is_single'] || $data['is_page'])
    {
      if (!empty($data['categories']))
      {
        $breadcrumbs = array();
        foreach ($data['categories'] as $key => $category)
        {
          $breadcrumbs[] = ucwords($category['name']);
        }
        $this->addBreadcrumb(implode(', ', $breadcrumbs));
      }

      $this->addBreadcrumb($data['title']);
      $this->prependTitle($data['title']);
    }
    else if ($data['is_category'])
    {
      $data['category'] = ucwords($data['category']);

      $this->addBreadcrumb($this->__('Categories'));
      $this->addBreadcrumb($data['category']);
      $this->prependTitle($data['category']);
    }
    else if ($data['is_tag'])
    {
      $this->addBreadcrumb($this->__('Tags', null, 'blog'));
      $this->addBreadcrumb($data['tag']);
      $this->prependTitle($data['tag']);
    }

    return sfView::SUCCESS;
  }
}