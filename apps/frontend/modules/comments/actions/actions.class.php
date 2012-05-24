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

    $this->redirect($request->getReferer());
  }

}