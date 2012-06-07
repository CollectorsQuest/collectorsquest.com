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

  protected function executeMessagesCompose(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $collectors = CollectorQuery::create()
      ->filterByUsername("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->select('Username')
      ->find()->getArrayCopy();

    if (empty($collectors))
    {
      // try to find by display name instead
      $collectors = CollectorQuery::create()
        ->filterByDisplayName("%$q%", Criteria::LIKE)
        ->limit($limit)
        ->select('DisplayName')
        ->find()->getArrayCopy();
    }

    return $this->output($collectors);
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  protected function executeTagsEdit($request)
  {
    /** @var $q iceModelTagQuery */
    $q = iceModelTagQuery::create()
      ->distinct()
      ->addAsColumn('id', 'Id')
      ->addAsColumn('name', 'LOWER(CONVERT(`Name` USING utf8))')
      ->addAsColumn('label', 'LOWER(CONVERT(`Name` USING utf8))')
      ->filterBy('Name', 'name LIKE "'. mb_strtolower($request->getParameter('term')).'%"', Criteria::CUSTOM)
      ->filterByIsTriple(false)
      ->orderBy('name', Criteria::ASC)
      ->select(array('id', 'name', 'label'))
      ->limit(10);
    $tags = $q->find()->getArrayCopy();

    return $this->output($tags);
  }
}
