<?php

class typeAheadAction extends cqAjaxAction
{
  protected function getObject(sfRequest $request)
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

  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  protected function executeTagsEdit($request)
  {
    $q = iceModelTagQuery::create()
      ->filterByName('%'.$request->getParameter('term').'%', Criteria::LIKE)
      ->filterByIsTriple(false)
      ->addAsColumn('id', 'Id')
      ->addAsColumn('name', 'Name')
      ->addAsColumn('label', 'Name')
      ->select(array('id', 'name', 'label'))
      ->limit(10);
    $tags = $q->find()->getArrayCopy();

    return $this->output($tags);
  }

  protected function executeShoppingOrderBuyerEmail($request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $email = ShoppingOrderQuery::create()
      ->filterByBuyerEmail("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'BuyerEmail');

    return $this->output($email);
  }

  protected function executeShoppingOrderShippingFullName($request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $names = ShoppingOrderQuery::create()
      ->filterByShippingFullName("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'ShippingFullName');

    return $this->output($names);
  }

  protected function executeSentEmailSenderEmail($request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $names = SentEmailQuery::create()
      ->filterBySenderEmail("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'SenderEmail');

    return $this->output($names);
  }

  protected function executeSentEmailReceiverEmail($request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $names = SentEmailQuery::create()
      ->filterByReceiverEmail("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'ReceiverEmail');

    return $this->output($names);
  }

  protected function executeSentEmailSubject($request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $names = SentEmailQuery::create()
      ->filterBySubject("%$q%", Criteria::LIKE)
      ->limit($limit)
      ->find()
      ->toKeyValue('Id', 'Subject');

    return $this->output($names);
  }
}
