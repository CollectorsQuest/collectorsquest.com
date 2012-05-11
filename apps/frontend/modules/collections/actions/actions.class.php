<?php

class collectionsActions extends cqFrontendActions
{

  public function executeIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeCategory()
  {
    $this->category = $this->getRoute()->getObject();

    return sfView::SUCCESS;
  }

  /**
   * Action Collector
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeCollector(sfWebRequest $request)
  {
    $this->collector = $this->getRoute()->getObject();

    return sfView::SUCCESS;
  }

}
