<?php

class typeAheadAction extends cqAjaxAction
{
  protected function getObject(sfRequest $request)
  {
    return null;
  }

  protected function executeMessagesCompose(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    if (strlen($q) >= 3)
    {
      // require query to be more than three chars to query the DB
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
    }
    else
    {
      // if query is less than three chars, return an empty result set
      $collectors = array();
    }

    return $this->output($collectors);
  }

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  protected function executeTagsEdit($request)
  {
    $term = mb_strtolower($request->getParameter('term'));
    $term = Propel::getConnection('propel')->quote(
      str_replace('%', '', $term).'%', PDO::PARAM_STR
    );

    /* @var $q iceModelTagQuery */
    $q = iceModelTagQuery::create()
      ->addAsColumn('id', 'Id')
      ->addAsColumn('normalized_name', 'LOWER(CONVERT(`Name` USING utf8))')
      ->addAsColumn('normalized_label', 'LOWER(CONVERT(`Name` USING utf8))');

    $q->filterBy('Name', 'name LIKE '. $term, Criteria::CUSTOM)
      ->filterBy('IsTriple', false)
      ->orderBy('name', Criteria::ASC)
      ->select(array('id', 'normalized_name', 'normalized_label'))
      ->groupBy('id')
      ->limit(10);

    /* @var $tags array */
    $tags = array_map(function($tag) {
      $tag['name'] = $tag['normalized_name'];
      $tag['label'] = $tag['normalized_label'];
      unset ($tag['normalized_name'], $tag['normalized_label']);

      return $tag;
    }, $q->find()->getArrayCopy());

    return $this->output($tags);
  }

  public function executeStatesLookup(sfWebRequest $request)
  {
    $states = iceModelGeoRegionQuery::create()
      ->orderByNameLatin()
      ->useiceModelGeoCountryQuery()
        ->filterByIso3166((string) $request->getParameter('c'))
      ->endUse()
      ->select(array('Id', 'NameLatin'))
      ->find()
      ->toKeyValue('Id', 'NameLatin');

    return $this->renderText(json_encode($states));
  }
}
