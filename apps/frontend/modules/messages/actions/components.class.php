<?php

class messagesComponents extends cqFrontendComponents
{
  public function executeSidebar()
  {

  }
  
  public function executeInboxTable(sfWebRequest $request)
  {
    $this->filter_by = $request->getParameter('filter', 'all');
    
    $is_search = $request->hasParameter('search');
    $search = '%'.$request->getParameter('search').'%';

    $q = PrivateMessageQuery::create()
      ->filterByCollectorRelatedByReceiver($this->getCollector())
      ->_if('read' == $this->filter_by)
        ->filterByIsRead(true)
      ->_elseif('unread' == $this->filter_by)
        ->filterByIsRead(false)
      ->_endif()
      ->filterByIsDeleted(false)
      ->_if($is_search)
        //->joinCollectorRelatedBySender(null, Criteria::LEFT_JOIN)
        ->filterBySubject($search)
        ->_or()
        ->filterByBody($search)
        ->_or()
        ->useCollectorRelatedBySenderQuery()
          ->filterByDisplayName($search)
          ->_or()
          ->filterByUsername($search)
        ->endUse()
      ->_endif()
      ->orderByCreatedAt(Criteria::DESC);
    
    $pager = new PropelModelPager($q);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    
    $this->pager = $pager;
  }
}