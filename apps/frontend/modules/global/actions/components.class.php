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

  public function executePagination()
  {
    /** @var $pager sfPropelPager */
    $pager = $this->getVar('pager');

    if (!$pager instanceof sfPager && !$pager instanceof PropelModelPager) {
      return sfView::NONE;
    }

    /** @var $options array */
    $options = $this->getVar('options');

    $options['title'] = (!empty($options['title']) && stripos($options['title'], '%d')) ? $options['title'] : __('Page %d');
    $options['page_param'] = @$options['page_param'] ?: 'page';

    $url          = isset($options['url']) ? $options['url'] : $this->getRequest()->getUri();
    $questionMark = strpos($url, '?');
    $params       = array();
    if (false !== $questionMark)
    {
      $queryStr = substr($url, $questionMark + 1);
      $url      = substr($url, 0, $questionMark);
      foreach (explode('&', $queryStr) as $param)
      {
        $item = explode('=', $param);
        //Remove any 'page' and 'show' keys
        if (in_array($item[0], array($options['page_param'], 'show')))
        {
          continue;
        }
        $params[$item[0]] = $item[1];
      }
    }

    $url .= '?' . http_build_query($params);
    $mark = !empty($params) ? '&' : '';

    // Set a unique div ID if not provided
    $options['id'] = !empty($options['id']) ? $options['id'] : 'pagination-'. md5($options['url']);

    $this->pager   = $pager;
    $this->options = $options;
    $this->url = $url;
    $this->mark = $mark;

    return sfView::SUCCESS;
  }

  public function executeFooter()
  {
    $this->signup_form = new CollectorSignupFooterForm();
    $this->login_form  = new CollectorLoginForm();

    return sfView::SUCCESS;
  }
}
