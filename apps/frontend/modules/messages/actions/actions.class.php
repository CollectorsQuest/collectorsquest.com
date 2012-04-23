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
      ->filterByCollectorRelatedByReceiver($this->getCollector())
      ->_if('read' == $this->filter_by)
        ->filterByIsRead(true)
      ->_elseif('unread' == $this->filter_by)
        ->filterByIsRead(false)
      ->_endif()
      ->filterByIsDeleted(false)
      ->orderByCreatedAt(Criteria::DESC)
      ->find();

    return sfView::SUCCESS;
  }

  public function executeSent(sfWebRequest $request)
  {
    $this->messages = PrivateMessageQuery::create()
      ->filterByCollectorRelatedBySender($this->getCollector())
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
    foreach ($messages as $a_message)
    {
      if ($a_message->getReceiver() == $this->getUser()->getId())
      {
        $a_message->setIsRead(true);
      }
    }
    // propel is smart and no update query will be executed if we
    // did not change any object
    $messages->save();

    $this->messages = $messages;
    $this->reply_form = new ComposePrivateMessageForm(
      $this->getCollector(), $message->getThread()
    );
    $this->reply_form->setDefault('subject', $messages->getLast()->getReplySubject());

    return sfView::SUCCESS;
  }

  public function executeCompose(sfWebRequest $request)
  {
    $form = new ComposePrivateMessageForm($sender = $this->getCollector());

    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $message_data = array(
            'subject' => $form->getValue('subject'),
            'body' => $form->getValue('body'),
            'thread' => $form->getValue('thread'),
        );
        /* @var Collector */
        $receiver = $form->getValue('receiver');

        $message = PrivateMessagePeer::send($receiver, $sender, $message_data);

        $this->redirect('messages_show', $message);
      }
      else
      {
        // Set the error message
        $this->getUser()->setFlash(
          "error", $this->__("There is a problem with sending your message.")
        );
      }
    }

    $this->form = $form;

    return sfView::SUCCESS;
  }

}
