<?php

class ajaxAction extends cqAjaxAction
{

  protected function getObject(sfRequest $request)
  {
    return null;
  }

  protected function executeHeaderTypeahead(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $categories = ContentCategoryQuery::create()
      ->filterByName("%$q%", Criteria::LIKE)
      ->filterByName("%&%", Criteria::NOT_LIKE)
      ->filterByName("%,%", Criteria::NOT_LIKE)
      ->joinCollectorCollection(null, Criteria::RIGHT_JOIN)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'Name');

    // Make sure we do not have duplicate names
    $categories = array_unique($categories);

    return $this->output($categories);
  }

}
