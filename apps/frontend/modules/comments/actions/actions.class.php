<?php

class commentsActions extends cqFrontendActions
{

  public function executeAddComment(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      $form = new FrontendCommentForm($this->getUser());
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $comment = $form->save();
        $cqEmail = new cqEmail($this->getMailer());

        if (method_exists($comment->getModelObject(), '__toString'))
        {
          if (method_exists($comment->getModelObject(), 'getCollector'))
          {
            $owner = $comment->getModelObject()->getCollector();

            if (!$this->getUser()->isAuthenticated() || $this->getCollector()->getId() != $owner->getId())
            {
              $cqEmail->send('Comments/new_comment_on_owned_item_notification', array(
                  'to' => $owner->getEmail(),
                  'params' => array(
                      'oOwner' => $owner,
                      'oModelObject' => $comment->getModelObject(),
                      'oNewComment' => $comment,
                      'sThreadUrl' => $request->getReferer() . '#comments',
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
    return $this->renderText('g');
  }

  public function executeLoadMoreComments(sfWebRequest $request)
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

  public function executeUnsubscribe(sfWebRequest $request)
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
        if ( urldecode($request->getParameter('email')) == $comment->getEmail())
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

}
