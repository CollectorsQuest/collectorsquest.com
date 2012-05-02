<?php

class typeAheadAction extends IceAjaxAction
{
  protected function getObject(sfWebRequest $request)
  {
    return null;
  }

  protected function executeCollectorUsername(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $usernames = CollectorQuery::create()
      ->filterByUsername("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'Username');

    return $this->output($usernames);
  }

  protected function executeCollectorDisplayName(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $names = CollectorQuery::create()
      ->filterByDisplayName("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'DisplayName');

    return $this->output($names);
  }

  protected function executeCollectibleName(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $names = CollectibleQuery::create()
        ->filterByName("%$q%", Criteria::LIKE)
        ->limit($limit)
        ->find()
        ->toKeyValue('Id', 'Name');

    return $this->output($names);
  }
}
