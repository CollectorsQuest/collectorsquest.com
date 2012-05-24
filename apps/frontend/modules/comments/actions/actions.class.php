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
        $form->save();
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

}