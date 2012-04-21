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

}
