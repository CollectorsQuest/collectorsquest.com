<?php

class commentsActions extends cqActions
{
  /**
   * Saves a comment
   *
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeComment(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      $comment = $request->getParameter('comment');
      $referer = isset($comment['referer']) ? $comment['referer'] : '@homepage';

      // We need a name for the database so let's make it Anonymous if needed
      if (isset($comment['name']) && $comment['name'] == '')
      {
        $comment['name'] = $this->__('Anonymous');
      }

      $form = new CommentForm();
      $form->bind($comment);

      if ($form->isValid())
      {
        if ($comment = $form->doSave())
        {
          if ($collectible = $comment->getCollectible())
          {
            $collector = $collectible->getCollector();
          }
          else if ($collection = $comment->getCollection())
          {
            $collector = $collection->getCollector();
          }

          if ($collector)
          {
            if ($email = $collector->getEmail())
            {
              if ($comment->getCollector())
              {
                $author_name = (string) $comment->getCollector();
              }
              else
              {
                $author_name = $comment->getAuthorName();
              }

              $subject = $this->__(
                '%author_name% commented on your collection %collection_name%',
                array('%author_name%' => $author_name, '%collection_name%' => (string) $comment->getCollection())
              );
              $body = $this->getPartial(
                'emails/comment_notification_owner',
                array('collector' => $collector, 'author_name' => $author_name, 'comment' => $comment)
              );
              $this->sendEmail($email, $subject, $body);
            }
          }

          // Set the success message
          $this->getUser()->setFlash('success', $this->__('Your comment was posted!', null, 'ice_comments'), true);
          $referer .= '#comments';
        }
        else
        {
          // Set the error message
          $this->getUser()->setFlash('error', $this->__('There was an error saving your comment or our system thinks it is spam!'), true);
          $referer .= '#comment-form';
        }
      }
      else
      {
        // Set the error message
        $this->getUser()->setFlash('error', $this->__('There was an error while posting your comment!'), true);
        $referer .= '#comment-form';
      }

      // We should always have the referer here, failing over to @homepage for just in case
      $this->redirect($referer);
    }

    return sfView::ERROR;
  }

  public function executeShortcut(sfWebRequest $request)
  {
    /** @var $comment Comment */
    $comment = $this->getRoute()->getObject();
    $route = null;

    // Get access to route_for_* functions
    $this->loadHelpers('cqLinks');

    if ($collectible = $comment->getCollectible())
    {
      $route = route_for_collectible($collectible);
    }
    else if ($collection = $comment->getCollection())
    {
      $route = route_for_collection($collection);
    }
    else
    {
      $this->forward404();
    }

    $this->redirect($route .'#comment_'. $comment->getId());
  }
}
