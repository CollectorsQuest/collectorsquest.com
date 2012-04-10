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

    if (!$pager instanceof sfPager) {
      return sfView::NONE;
    }

    /** @var $options array */
    $options = $this->getVar('options');

    $options['title'] = (!empty($options['title']) && stripos($options['title'], '%d')) ? $options['title'] : __('Page %d');
    $options['page_param'] = @$options['page_param'] ?: 'page';

    // Remove any "page=\d+" from the URL
    $options['url'] = preg_replace(
      '/(\?|&)?' . $options['page_param'] . '=\d+/iu', '',
      @$options['url'] ?: $this->getRequest()->getUri()
    );

    // Remove any "show=all" from the URL
    $options['url'] = preg_replace(
      '/(\?|&)show=all(\?|&|$)/iu', '$1',
      @$options['url'] ?: $this->getRequest()->getUri()
    );

    $options['url'] = rtrim($options['url'], '?&');
    $options['url'] .= (strpos($options["url"], '?') !== false) ? '&' : '?';

    // Set a unique div ID if not provided
    $options['id'] = !empty($options['id']) ? $options['id'] : 'pagination-'. md5($options['url']);

    $this->pager   = $pager;
    $this->options = $options;

    return sfView::SUCCESS;
  }

  public function executeFooter()
  {
    $this->signup_form = new CollectorSignupFooterForm();
    $this->login_form  = new CollectorLoginForm();

    return sfView::SUCCESS;
  }
}
