<?php

class collectiblesComponents extends cqFrontendComponents
{
  /**
   * Should only be used with these routes:
   *
   * @uri     /collectibles/by/:id/:slug
   * @uri     /collectibles-for-sale/by/:id/:slug
   */
  public function executeSidebarCollectorList(sfWebRequest $request)
  {
    $this->collector = $request->getAttribute('sf_route')->getObject();
  }

}