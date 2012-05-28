<?php

class collectorsComponents extends cqFrontendComponents
{

  /**
   * Action Sidebar
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeSidebar(sfWebRequest $request)
  {
    $sortBy = $request->getParameter('sort', 'latest');

    // Current URL
    $action = 'sellers' == $request->getParameter('type', 'collectors') ? 'sellers' : 'collectors';

    $this->sortBy = array(
      'most-popular' => array(
        'name'   => 'Most Popular',
        'active' => 'most-popular' == $sortBy || empty($sortBy),
        'route'  => $this->generateUrl($action, array('sort'=> 'most-popular')),
      ),
      'near-you'     => array(
        'name'   => 'Near you',
        'active' => 'near-you' == $sortBy,
        'route'  => $this->generateUrl($action, array('sort'=> 'near-you')),
      ),
      'latest'       => array(
        'name'   => 'Latest',
        'active' => 'latest' == $sortBy,
        'route'  => $this->generateUrl($action, array('sort'=> 'latest')),
      ),
    );
  }

}
