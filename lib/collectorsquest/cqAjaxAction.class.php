<?php

/**
 * @method cqUser getUser()
 *
 * @method sfWebRequest getRequest()
 * @method sfWebResponse getResponse()
 */
abstract class cqAjaxAction extends sfAction
{
  protected function json($data)
  {
    $json = json_encode($data);

    if (SF_ENV == 'dev' && !$this->getRequest()->isXmlHttpRequest())
    {
      $this->getContext()->getConfiguration()->loadHelpers('Partial');
      $json = get_partial('ajax/json', array('data' => $data));
    }
    else
    {
      $this->getResponse()->setHttpHeader('Content-type', 'application/json');
      if (strlen($json) < 4028)
      {
        $this->getResponse()->setHttpHeader('X-JSON', $json);
      }
    }

    $this->renderText($json);
    return sfView::NONE;
  }
}
