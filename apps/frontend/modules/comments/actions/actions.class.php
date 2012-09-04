<?php

class commentsActions extends cqFrontendActions
{

  /**
   * Add a comment for any eligeble object in the site;
   *
   * Combine with the comments/addComment or comments/comments component
   */
  public function executeAddComment(cqWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      $form = new FrontendCommentForm($this->getUser());
      $form->bind(array_merge(
        $request->getParameter($form->getName()),
        array(
          'ip_address' => $request->getRemoteAddress(),
        )
      ));

      if ($form->isValid())
      {
        $form->getObject()->setIpAddress($request->getRemoteAddress());
        $comment = $form->save();
        $cqEmail = new cqEmail($this->getMailer());

        if (method_exists($comment->getModelObject(), '__toString'))
        {
          if (method_exists($comment->getModelObject(), 'getCollector'))
          {
            $owner = $comment->getModelObject()->getCollector();

            // if the user is not authenticated or not the onwer of the object being
            // commented on and wants to receive comment notifications
            if (
              !$this->getUser()->isAuthenticated() ||
              ( $this->getCollector()->getId() != $owner->getId() &&
                $owner->getNotificationsComment()
              )
            ) {
              $ret = $cqEmail->send('Comments/new_comment_on_owned_item_notification', array(
                  'to' => $owner->getEmail(),
                  'params' => array(
                      'oOwner' => $owner,
                      'oModelObject' => $comment->getModelObject(),
                      'oNewComment' => $comment,
                      'sThreadUrl' => $request->getReferer(),
                      'sCommentRemoveUrl' => $this->getController()->genUrl(array(
                          'sf_route' => 'comments_delete',
                          'sf_subject' => $comment,
                        ), true),
                  ),
              ));
            }
          }

          $notify_comments = CommentQuery::create()
            ->filterByModelObject($comment->getModelObject())
            ->filterByIsNotify(true)
            ->_if(isset($owner))
              ->filterByCollectorId($owner->getId(), Criteria::NOT_EQUAL)
            ->_endif()
            ->groupByAuthorEmail()
            ->groupByCollectorId()
            ->find();

          foreach ($notify_comments as $notify_comment)
          {
            if ($comment->getAuthorEmail() == $notify_comment->getAuthorEmail())
            {
              // don't notify ourselves about our own comment ;)
              continue;
            }

            $cqEmail->send('Comments/comment_response_notification', array(
                'to' => $notify_comment->getAuthorEmail(),
                'params' => array(
                    'oModelObject' => $comment->getModelObject(),
                    'oNewComment' => $comment,
                    'oYourComment' => $notify_comment,
                    'sThreadUrl' => $request->getReferer() . '#comments',
                    'sUnsubscribeUrl' => $this->generateUrl('comments_unsubscribe', array(
                        'email' => urlencode($notify_comment->getAuthorEmail()),
                        'model_class' => $notify_comment->getModelObjectClass(),
                        'model_pk' => $notify_comment->getModelObjectPk(),
                        'r' => $request->getReferer(),
                    ), true)
                ),
            ));
          }
        }
      }
      else
      {
        $this->getUser()->setFlash(
          'comment_error', $form->getErrorSchema()->__toString()
        );
      }
    }

    return $this->redirect($request->getReferer() . "#comments");
  }

  /**
   * Ajax load more comments
   */
  public function executeLoadMoreComments(cqWebRequest $request)
  {
    $token = $request->getParameter('token');
    $offset = $request->getParameter('offset');

    if ( $request->isXmlHttpRequest() && $token && $offset
      && $model_object = CommentPeer::retrieveFromCommentableToken(
                                      $token, $this->getUser()) )
    {
      $comments = CommentQuery::create()
        ->filterByModelObject($model_object)
        ->leftJoinCollector()
        ->orderByCreatedAt(Criteria::DESC)
        ->limit(sfConfig::get('app_comments_num_load', 20))
        ->offset($offset)
        ->find();

      $html = '';

      foreach ($comments as $comment)
      {
        $html .= $this->getPartial('single_comment', array('comment' => $comment));
      }

      return $this->renderText(json_encode(array(
          'html' => $html,
          'has_more' => $comments->count() == sfConfig::get('app_comments_num_load', 20),
      )));
    }

    return sfView::NONE;
  }

  /**
   * Unsubscribe from a comment thread
   */
  public function executeUnsubscribe(cqWebRequest $request)
  {
    if (( $model_object = CommentPeer::retrieveCommentableObject(
      $request->getParameter('model_class'),
      $request->getParameter('model_pk')) ))
    {
      $comments = CommentQuery::create()
        ->filterByModelObject($model_object)
        ->leftJoinCollector()
        ->find();

      foreach ($comments as $comment)
      {
        if (urldecode($request->getParameter('email')) == $comment->getEmail())
        {
          $comment->setIsNotify(false);
        }
      }
      $comments->save();

      $this->getUser()->setFlash(
        'comment_success',
        'You have successfully unsubscribed from new comment notifications.'
      );
    }

    $this->redirect($request->getParameter('r', '@homepage') . '#comments');
  }

  /**
   * Delete a comment if the commented object is owned by the currently logged in
   * collector. Orherwize forward 404. A confirmation is requried for the deletion
   *
   * PropelObjectRoute for Comment
   */
  public function executeDelete(cqWebRequest $request)
  {
    /** @var Comment */
    $comment = $this->getRoute()->getObject();

    $this->forward404Unless(
      // owner of the object that was commented on
      $this->getCollector()->isOwnerOf($comment->getModelObject()) ||
      // owner of the comment itself
      $this->getCollector()->isOwnerOf($comment)
    );

    $form = new CommentDeleteConfirmationForm();
    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $comment->delete();
        $this->getUser()->setFlash('comment_success', 'Comment successfully deleted.');

        return $this->redirect(
          $this->getController()->genUrlForModelObject($comment).'#comments'
        );
      }
    }

    $this->comment = $comment;
    $this->form = $form;

    return sfView::SUCCESS;
  }

}
