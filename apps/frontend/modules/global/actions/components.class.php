<?php

class globalComponents extends cqFrontendComponents
{
  public function executeHeader()
  {
    $this->form = new SearchHeaderForm();

    return sfView::SUCCESS;
  }

  public function executeHeaderStripped()
  {
    return sfView::SUCCESS;
  }

  public function executeBreadcrumbs()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebar120()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebar160()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebar180()
  {
    return sfView::SUCCESS;
  }

  public function executeSidebar300()
  {
    return sfView::SUCCESS;
  }

  public function executeJquery()
  {
    return sfView::SUCCESS;
  }

  public function executePagination(sfWebRequest $request)
  {
    /** @var $pager sfPropelPager */
    $pager = $this->getVar('pager');

    if (!$pager instanceof sfPager && !$pager instanceof PropelModelPager)
    {
      return sfView::NONE;
    }

    /** @var $options array */
    $options = $this->getVar('options');

    // Figure out the title of the link
    $options['title'] = (!empty($options['title']) && stripos($options['title'], '%d')) ?
      $options['title'] :
      __('Page %d');

    // We sometimes need to overwrite the name of the $_GET varibale for "page"
    $options['page_param'] = @$options['page_param'] ?: 'page';

    $params = array();
    $url = isset($options['url']) ? $options['url'] : $this->getRequest()->getUri();

    $questionMark = strpos($url, '?');
    if (false !== $questionMark)
    {
      $url = substr($url, 0, $questionMark);
    }

    $pathInfo = $request->getPathInfoArray();
    $queryStr = $pathInfo['QUERY_STRING'];
    if ($queryStr)
    {
      foreach (explode('&', $queryStr) as $param)
      {
        $item = explode('=', $param);

        //Remove any 'page' and 'show' keys
        if (in_array($item[0], array($options['page_param'], 'show')))
        {
          continue;
        }

        $params[$item[0]] = isset($item[1]) ? urldecode($item[1]) : null;
      }
    }

    $url .= '?' . http_build_query($params);
    $mark = !empty($params) ? '&' : '';

    // Set a unique div ID if not provided
    $options['id'] = !empty($options['id']) ? $options['id'] : 'pagination-'. md5($url);

    $this->pager   = $pager;
    $this->options = $options;
    $this->url = $url;
    $this->mark = $mark;

    return sfView::SUCCESS;
  }

  public function executeFooter()
  {
    $this->signup_form = new CollectorSignupFooterForm();
    $this->login_form  = new CollectorLoginFooterForm();

    return sfView::SUCCESS;
  }

  public function executeEmpty()
  {
    return sfView::SUCCESS;
  }

  public function executeAdminBar()
  {
    if (!$this->getUser()->isAdmin() || $this->getRequest()->isMobileBrowserFitLayout())
    {
      return sfView::NONE;
    }

    $this->items = cqAdminBar::getMenu();

    return sfView::SUCCESS;
  }
}
