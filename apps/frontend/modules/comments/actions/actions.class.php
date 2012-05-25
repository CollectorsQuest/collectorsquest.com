<?php

class commentsActions extends cqFrontendActions
{

  public function executeAddComment(sfWebRequest $request)
  {
    if (sfRequest::POST == $request->getMethod())
    {
      $form = new FrontendCommentForm($this->getUser());
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $comment = $form->save();


        if (method_exists($comment->getModelObject(), '__toString'))
        {
          $notify_comments = CommentQuery::create()
            ->filterByModelObject($comment->getModelObject())
            ->filterByIsNotify(true)
            ->groupByAuthorEmail()
            ->groupByCollectorId()
            ->find();

          $cqEmail = new cqEmail($this->getMailer());
          foreach ($notify_comments as $notify_comment)
          {
            if ($comment->getEmail() == $notify_comment->getEmail())
            {
              // don't notify ourselves about our own comment ;)
              continue;
            }

            $res = $cqEmail->send('Comments/comment_response_notification', array(
                'to' => $notify_comment->getEmail(),
                'params' => array(
                    'model_object' => $comment->getModelObject(),
                    'new_comment' => $comment,
                    'user_comment'  => $notify_comment,
                    'thread_url' => $request->getReferer() . '#comments',
                    'unsubscribe_url' => $this->generateUrl('comments_unsubscribe', array(
                        'email' => urlencode($notify_comment->getEmail()),
                        'model_class' => $notify_comment->getModelObjectClass(),
                        'model_pk' => $notify_comment->getModelObjectPk(),
                        'goto' => $request->getReferer(),
                    ), true)
                ),
            ));
          }
        }

        $this->getUser()->setFlash('comment_success', 'Comment successfully added!');
      }
      else
      {
        $this->getUser()->setFlash('comment_error',
          $form->getErrorSchema()->__toString());
      }
    }

    $this->redirect($request->getReferer() . "#comments");
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

      $this->getUser()->setFlash('comment_success', 'You have successfully unsubscribed from new comment notifications.');
    }

    $this->redirect($request->getParameter('goto', '@homepage') . '#comments');
  }

}
