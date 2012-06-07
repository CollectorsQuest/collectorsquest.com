<?php

class commentsComponents extends sfComponents
{

  /**
   * Diplays the commenting form
   *
   * @param $request
   */
  public function executeCommentForm(sfWebRequest $request)
  {
    // build the comment form
    $this->form = new CommentForm();

    $comment = $request->getParameter('comment');
    if ($request->isMethod('post') && is_array($comment))
    {
      $this->form->bind($comment);
    }
    else
    {
      $token = CommentForm::addTokenToSession(get_class($this->object), $this->object->getId());

      $this->form->setDefaults(array(
        'referer' => str_replace($request->getUriPrefix(), '', $request->getUri()),
        'token'   => $token
      ));
    }

    return sfView::SUCCESS;
  }

  /**
   * Displays the list of the comments
   * @param sfWebRequest $request
   * @return string
   */
  public function executeCommentList($request)
  {
    /* @var $object Collection */
    $object = $this->object;
    $order = $this->order;
    $pager = new sfPropelPager('Comment');

    $criteria = new Criteria();

    switch (get_class($object))
    {
      case 'CollectorCollection':
      case 'Collection':
        $criteria->add(CommentPeer::COLLECTION_ID, $object->getId());
        break;

      case 'Collector':
        $criteria->add(CommentPeer::COLLECTOR_ID, $object->getId());
        break;

      case 'Collectible':
        $criteria->add(CommentPeer::COLLECTIBLE_ID, $object->getId());
        break;
    }

    if ($this->limit !== null)
    {
      $criteria->setLimit($this->limit);
      $pager->setMaxRecordLimit($this->limit);
    }

    switch ($this->order)
    {
      case 'asc':
        $criteria->addAscendingOrderByColumn(CommentPeer::ID);
        break;
      case 'desc':
      default:
        $criteria->addDescendingOrderByColumn(CommentPeer::ID);
        break;
    }


    $pager->setCriteria($criteria);
    $pager->setPage($request->getParameter('cpage', 1));
    $pager->setMaxPerPage(sfConfig::get('app_comments_per_page', 10));
    $pager->init();

    $this->pager = $pager;

    return sfView::SUCCESS;
  }
}
