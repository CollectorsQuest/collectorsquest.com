<?php

class ajaxAction extends cqAjaxAction
{
  protected function getObject(sfRequest $request)
  {
    $object = null;

    if ($request->getParameter('id'))
    {
      $object = CollectorQuery::create()->findOneById($request->getParameter('id'));
    }

    return $object;
  }

  /**
   * @param  sfWebRequest  $request
   * @return mixed
   */
  public function execute($request)
  {
    $this->collector = $this->getObject($request);

    // Stop here if there is no valid Collector or the Collector object is not saved yet
    $this->forward404if(!$this->collector || $this->collector->isNew());

    return parent::execute($request);
  }
}
