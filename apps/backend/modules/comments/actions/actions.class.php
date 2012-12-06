<?php

require_once dirname(__FILE__) . '/../lib/commentsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/commentsGeneratorHelper.class.php';

/**
 * comments actions.
 *
 * @package    CollectorsQuest
 * @subpackage comments
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class commentsActions extends autoCommentsActions
{

  /**
   * Action Block IP
   */
  public function executeBlockIp()
  {
    /* @var $comment Comment */
    $comment = $this->getRoute()->getObject();

    $ban_added = iceSpamControl::ban(
      'ip',
      $comment->getIpAddress(),
      $validate = true,
      iceSpamControl::CREDENTIALS_COMMENT
    );
    if ($ban_added)
    {
      $this->getUser()->setFlash(
        'notice',
        sprintf('Blocked %s as spam', $comment->getIpAddress())
      );
    }
    else
    {
      $this->getUser()->setFlash(
        'error',
        sprintf('%s already in blocked list', $comment->getIpAddress())
      );
    }

    return $this->redirect('@comment');
  }

  /**
   * Action Block Email
   */
  public function executeBlockEmail()
  {
    /* @var $comment Comment */
    $comment = $this->getRoute()->getObject();

    $ban_added = iceSpamControl::ban(
      'email',
      $comment->getAuthorEmail(),
      $validate = false,
      iceSpamControl::CREDENTIALS_COMMENT
    );
    if ($ban_added)
    {
      $this->getUser()->setFlash(
        'notice',
        sprintf('Blocked %s as spam', $comment->getAuthorEmail())
      );
    }
    else
    {
      $this->getUser()->setFlash(
        'error',
        sprintf('%s already in blocked list', $comment->getAuthorEmail())
      );
    }

    return $this->redirect('@comment');
  }

  /**
   * Action UpdateContent
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeUpdateContent(sfWebRequest $request)
  {
    $id      = $request->getParameter('id', false);
    $content = $request->getParameter('content');

    if (!$id)
    {
      return $this->renderText(json_encode(array(
        'status' => 'fail',
        'message'=> 'Invalid ID',
      )));
    }

    $comment = CommentPeer::retrieveByPK($id);

    if (!$comment)
    {
      return $this->renderText(json_encode(array(
        'status' => 'fail',
        'message'=> 'Comment not found',
      )));
    }

    $comment->setBody($content);
    $comment->save();

    return $this->renderText(json_encode(array('status'=> 'success')));
  }


  public function executeBlockAndDelete(sfWebRequest $request)
  {
    $comment = CommentPeer::retrieveByPK($request->getParameter('id'));

    if ($comment)
    {
      iceSpamControl::ban(
        'email',
        $comment->getAuthorEmail(),
        $validate = false,
        iceSpamControl::CREDENTIALS_COMMENT
      );
      iceSpamControl::ban(
        'ip',
        $comment->getIpAddress(),
        $validate = true,
        iceSpamControl::CREDENTIALS_COMMENT
      );

      $this->getUser()->setFlash(
        'notice',
        sprintf('Blocked %s (%s) as spam and deleted the comment',
          $comment->getAuthorEmail(),
          $comment->getIpAddress()
        )
      );

      $comment->delete();
    }

    return $this->redirect('@comment');
  }

}
