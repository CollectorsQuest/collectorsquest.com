<?php

class commentsComponents extends cqFrontendComponents
{

  public function executeComments()
  {
    $this->checkForObjectIsValid();

    if (!isset($this->with_controls))
    {
      $this->with_controls = $this->getUser()->isOwnerOf($this->for_object);
    }
  }

  public function executeShowComments()
  {
    $this->checkForObjectIsValid();

    if (!isset($this->with_controls))
    {
      $this->with_controls = $this->getUser()->isOwnerOf($this->for_object);
    }

    $this->comments = CommentQuery::create()
      ->filterByModelObject($this->for_object)
      ->leftJoinCollector()
      ->orderByCreatedAt(Criteria::DESC)
      ->limit(sfConfig::get('app_comments_num_load', 20))
      ->find();
  }

  public function executeAddComment()
  {
    $this->checkForObjectIsValid();

    $this->form = new FrontendCommentForm($this->getUser(), $this->for_object);
    $this->form->setDefault('referer', $this->getRequest()->getUri());
  }

  /**
   * Check if a valid "for_object" option of was passed to the component.
   *
   * It should be an instance of BaseObject (a Propel model object);
   */
  protected function checkForObjectIsValid()
  {
    if (!isset($this->for_object) || !$this->for_object instanceof BaseObject)
    {
      throw new Exception (sprintf('Cannot show comments for object of type %s',
        isset($this->for_object)
          ? ( is_object($this->for_object) ? get_class($this->for_object) : gettype($this->for_object) )
          : "null"
      ));
    }
  }
}