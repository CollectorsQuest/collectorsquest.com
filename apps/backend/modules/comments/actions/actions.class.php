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
   * Action Block
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeBlock(sfWebRequest $request)
  {
    //    dd($request->getParameterHolder()->getAll(), $this->getRoute()->getObject());

    /* @var $comment Comment */
    $comment = $this->getRoute()->getObject();

    $spamControl = new iceModelSpamControl();
    $spamControl->setField('ip');
    $spamControl->setValue($comment->getIpAddress());
    try
    {
      $spamControl->save();
      $this->getUser()->setFlash('success', sprintf('Blocked %s as spam', $comment->getIpAddress()));
    } catch (PropelException $e)
    {
      $this->getUser()->setFlash('error', sprintf('%s already in blocked list', $comment->getIpAddress()));
    }

    $this->redirect('@comment');
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

}
