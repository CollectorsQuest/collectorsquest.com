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
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $form->getObject()->setIpAddress($request->getRemoteAddress());
        $comment = $form->save();
        $cqEmail = new cqEmail($this->getMailer());

        if (method_exists($comment->getModelObject(), '__toString'))
        {
          if (method_exists($comment->getModelObject(), 'getCollector'))
          {
            /* @var $owner Collector */
            $owner = $comment->getModelObject()->getCollector();

            // if the user is not authenticated or not the owner of the object being
            // commented on and wants to receive comment notifications
            if (
              !$this->getUser()->isAuthenticated() ||
              (!$owner->equals($this->getCollector()) && $owner->getNotificationsComment())
            )
            {
              $ret = $cqEmail->send('Comments/new_comment_on_owned_item_notification', array(
                  'to' => $owner->getEmail(),
                  'params' => array(
                      'oOwner' => $owner,
                      'oModelObject' => $comment->getModelObject(),
                      'oNewComment' => $comment,
                      'sThreadUrl' => $request->getReferer(),
                      'sCommentRemoveUrl' => $this->getController()->genUrl(array(
                          'sf_route' => 'comments_hide',
                          'sf_subject' => $comment,
                        ), $absolute_url = true),
                      'sCommentReportSpamUrl' => $this->getController()->genUrl(array(
                          'sf_route' => 'comments_report_spam',
                          'sf_subject' => $comment,
                        ), $absolute_url = true),
                  ),
              ));
            }
          }

          /* @var $notify_comments Comment[] */
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

          $this->getUser()->setFlash('success', 'Your comment was successfully added.', 'comment');
        }
      }
      else
      {

        $this->getUser()->setFlash(
          'error',
          $form->renderAllErrors('There was a problem with posting your comment:'),
          'comment'
        );
      }
    }

    return $this->redirect($request->getReferer() . '#comments');
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
        $html .= $this->getPartial('single_comment', array(
            'comment' => $comment,
            'with_controls' =>  $this->getUser()->isOwnerOf($this->for_object)
        ));
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
    $model_object = CommentPeer::retrieveCommentableObject(
      $request->getParameter('model_class'),
      $request->getParameter('model_pk')
    );

    if ($model_object)
    {
      /* @var $comments PropelObjectCollection */
      $comments = CommentQuery::create()
        ->filterByModelObject($model_object)
        ->leftJoinCollector()
        ->find();

      /* @var $comment Comment */
      foreach ($comments as $comment)
      {
        if (urldecode($request->getParameter('email')) == $comment->getEmail())
        {
          $comment->setIsNotify(false);
        }
      }
      $comments->save();

      $this->getUser()->setFlash(
        'success',
        'You have successfully unsubscribed from new comment notifications.',
        'comment'
      );
    }

    $this->redirect($request->getParameter('r', '@homepage') . '#comments');
  }

  /**
   * A separate manage page for users with javascript disabled
   */
  public function executeManage(cqWebRequest $request)
  {
    /* @var $comment Comment */
    $comment = $this->getRoute()->getObject();

    $this->forward404Unless(
      // owner of the object that was commented on
      $this->getUser()->isOwnerOf($comment->getModelObject()) ||
      // owner of the comment itself
      $this->getUser()->isOwnerOf($comment)
    );

    $this->is_object_owner = $this->getUser()->isOwnerOf($comment->getModelObject());
    $this->comment = $comment;

    return sfView::SUCCESS;
  }

  /**
   * Hide a comment (available only to the owner of the object for which
   * the comment was created)
   */
  public function executeHide(cqWebRequest $request)
  {
    /* @var $comment Comment */
    $comment = $this->getRoute()->getObject();

    $this->forward404Unless(
      (
      // owner of the object that was commented on
      $this->getUser()->isOwnerOf($comment->getModelObject()) ||
      // owner of the comment itself
      $this->getUser()->isOwnerOf($comment)
      ) &&
      !$comment->getIsHidden()
    );

    if (sfRequest::POST == $request->getMethod())
    {
      $comment->setIsHidden(true);
      $comment->save();

      if ($request->isXmlHttpRequest())
      {
        return $this->renderText(json_encode(array(
            'status' => 'success',
        )));
      }
      else
      {
        $this->getUser()->setFlash('success', 'Comment successfully hidden.', 'comment');

        return $this->redirect(
          $this->getController()->genUrlForModelObject($comment).'#comments'
        );
      }
    }

    $this->comment = $comment;

    return sfView::SUCCESS;
  }

  /**
   * Unhide a comment (available only to the owner of the object for which
   * the comment was created)
   */
  public function executeUnhide(cqWebRequest $request)
  {
    /* @var $comment Comment */
    $comment = $this->getRoute()->getObject();

    // forward 404 unless logged in user is owner of the object that was commented on
    $this->forward404Unless(
      $this->getUser()->isOwnerOf($comment->getModelObject()) &&
      $comment->getIsHidden()
    );

    if (sfRequest::POST == $request->getMethod())
    {
      $comment->setIsHidden(false);
      $comment->save();

      if ($request->isXmlHttpRequest())
      {
        return $this->renderText(json_encode(array(
            'status' => 'success',
        )));
      }
      else
      {
        $this->getUser()->setFlash('success', 'Comment successfully unhidden.', 'comment');

        return $this->redirect(
          $this->getController()->genUrlForModelObject($comment)
          .'#comment-'.$comment->getId()
        );
      }
    }

    $this->comment = $comment;

    return sfView::SUCCESS;
  }

  /**
   * Delete a comment if the commented object is owned by the currently logged in
   * collector. Orherwize forward 404. A confirmation is requried for the deletion
   *
   * PropelObjectRoute for Comment
   */
  public function executeDelete(cqWebRequest $request)
  {
    /* @var $comment Comment */
    $comment = $this->getRoute()->getObject();

    $this->forward404Unless(
      // owner of the object that was commented on
      $this->getUser()->isOwnerOf($comment->getModelObject()) ||
      // owner of the comment itself
      $this->getUser()->isOwnerOf($comment)
    );

    if ($this->getUser()->isOwnerOf($comment))
    {
      $form = new CommentDeleteConfirmationForm();
      if (sfRequest::POST == $request->getMethod())
      {
        $form->bind($request->getParameter($form->getName()));

        if ($form->isValid())
        {
          $comment->delete();
          $this->getUser()->setFlash('success', 'Comment successfully deleted.', 'comment');

          return $this->redirect(
            $this->getController()->genUrlForModelObject($comment).'#comments'
          );
        }
      }

      $this->comment = $comment;
      $this->form = $form;

      return sfView::SUCCESS;
    }
    else
    {
      return $this->redirect('comments_hide', $comment);
    }
  }

  /**
   * Report a comment as spam. This will perma-hide the comment, and send an
   * email to the administrators who can judge if the offender should be banned
   */
  public function executeReportSpam(cqWebRequest $request)
  {
    /* @var $comment Comment */
    $comment = $this->getRoute()->getObject();

    $this->forward404Unless(
      // owner of the object that was commented on
      $this->getUser()->isOwnerOf($comment->getModelObject()) &&
      !$comment->getIsSpam()
    );
      $form = new CommentReportSpamConfirmationForm();
      if (sfRequest::POST == $request->getMethod())
      {
        $form->bind($request->getParameter($form->getName()));

        if ($form->isValid())
        {
          $comment->setIsHidden(true);
          $comment->setIsSpam(true);
          $comment->save();

          $this->getUser()->setFlash('success', 'Comment was successfully reported as spam. Thank you!', 'comment');

          $cqEmail = new cqEmail($this->getMailer());

          $cqEmail->send('internal/comment_spam_notification', array(
              'params' => array(
                  'oComment' => $comment,
                  'oReporterCollector' => $this->getCollector(),
                  'oModelObject' => $comment->getModelObject(),
              ),
          ));

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
