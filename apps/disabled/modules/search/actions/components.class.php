<?php

class searchComponents extends sfComponents
{
  public function executeSidebar()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'Advanced Search',
        'icon' => 'search',
        'route' => '@search_advanced?q='. $this->getRequestParameter('q', $this->getRequestParameter('tag')),
        'active' => ($this->getRequest()->getPathInfo() == '/search-advanced')
      )
    );

    // $this->amazon_products = cqStatic::getAmazonProducts(7, $this->getRequestParameter('q', $this->getRequestParameter('tag')));

    return sfView::SUCCESS;
  }
}
