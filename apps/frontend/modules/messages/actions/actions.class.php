<?php

/**
 * messages actions.
 *
 * @package    CollectorsQuest
 * @subpackage messages
 */
class messagesActions extends cqFrontendActions
{

  public function executeInbox(sfWebRequest $request)
  {
    // possible values: all, read, unread
    $this->filter_by = $request->getParameter('filter', 'all');

    $this->messages = PrivateMessageQuery::create()
      ->filterByCollectorRelatedByReceiver($this->getUser()->getCollector())
      ->_if('read' == $this->filter_by)
        ->filterByIsRead(true)
      ->_elseif('unread' == $this->filter_by)
        ->filterByIsRead(false)
      ->_endif()
      ->groupByThread()
      ->orderByCreatedAt(Criteria::DESC)
      ->find();

    return sfView::SUCCESS;
  }

  public function executeShow(sfWebRequest $request)
  {
    $message = $this->getRoute()->getObject();

    // forward to 404 if message is not for this user
    $this->forward404Unless(in_array(
      $this->getUser()->getId(),
      array($message->getSender(), $message->getReceiver())
    ));

    $messages = PrivateMessageQuery::create()
      ->filterByThread($message->getThread())
      ->orderByCreatedAt(Criteria::ASC)
      ->find();

    // mark messages as read if they are addressed to us
    foreach ($messages as $tmp_message)
    {
      if ($tmp_message->getReceiver() == $this->getUser()->getId())
      {
        $tmp_message->setIsRead(true);
      }
    }
    // propel is smart and no update query will be executed if we
    // did not change any object
    $messages->save();

    $this->messages = $messages;

    return sfView::SUCCESS;
  }

}
