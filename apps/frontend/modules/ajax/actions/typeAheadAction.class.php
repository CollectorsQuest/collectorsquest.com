<?php

class typeAheadAction extends IceAjaxAction
{
  protected function getObject(sfWebRequest $request)
  {
    return null;
  }

  protected function executeSearchHeader(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $categories = CollectionCategoryQuery::create()
      ->filterByName("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'Name');

    return $this->output($categories);
  }

}
