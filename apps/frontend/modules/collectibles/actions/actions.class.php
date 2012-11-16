<?php

/**
 * collectibles actions.
 */
class collectiblesActions extends cqFrontendActions
{

 /**
  * List collectibles for a particular collector
  *
  * @url  /collectibles/by/:id/:slug
  */
  public function executeCollectorList(sfWebRequest $request)
  {
    /* @var $collector Collector */
    $collector = $this->getRoute()->getObject();

    $this->redirect('collector_by_slug', $collector, 301);
  }

}
