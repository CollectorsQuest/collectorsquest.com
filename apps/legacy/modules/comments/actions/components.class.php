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
        'token' => $token
      ));
    }

    return sfView::SUCCESS;
  }

  /**
   * Displays the list of the comments
   */
  public function executeCommentList()
  {
    $object = $this->object;
    $order = $this->order;

    $criteria = new Criteria();

    if ($this->limit !== null)
    {
      $criteria->setLimit($this->limit);
    }

    switch ($this->order)
    {
      case 'asc':
        $criteria->addAscendingOrderByColumn(CommentPeer::CREATED_AT);
        $criteria->addAscendingOrderByColumn(CommentPeer::ID);
        break;
      case 'desc':
      default:
        $criteria->addDescendingOrderByColumn(CommentPeer::CREATED_AT);
        $criteria->addDescendingOrderByColumn(CommentPeer::ID);
        break;
    }

    $this->comments = $object->getComments($criteria);

    return sfView::SUCCESS;
  }
}