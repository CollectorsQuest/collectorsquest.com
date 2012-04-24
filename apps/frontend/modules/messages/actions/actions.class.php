<?php

/**
 * messages actions.
 *
 * @package    CollectorsQuest
 * @subpackage messages
 */
class messagesActions extends cqFrontendActions
{

  public function executeIndex()
  {
    return $this->redirect('@messages_inbox');
  }

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
    $form->setDefault('receiver', $request->getParameter('to'));
    $form->setDefault('subject', $request->getParameter('subject'));

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

        $cqEmail = new cqEmail($this->getMailer());
        $sent = $cqEmail->send('Messages/private_message_notification', array(
            'to' => $receiver->getEmail(),
            'params' => array(
              'sender' => $sender,
              'receiver' => $receiver,
            ),
        ));

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

  public function executeBatchActions(sfWebRequest $request)
  {
    // possible keys: mark_as_read, mark_as_unread, delete
    $action = $request->getParameter('batch_action');

    $q = PrivateMessageQuery::create()
      ->filterByPrimaryKeys($request->getParameter('ids'))
      ->filterByCollectorRelatedByReceiver($this->getCollector());

    if (isset($action['mark_as_unread']))
    {
      $q->update(array('IsRead' => false));
    }

    else if (isset($action['mark_as_read']))
    {
      $q->update(array('IsRead' => true));
    }

    else if (isset($action['delete']))
    {
      $q->update(array('IsDeleted' => true));
    }

    return $this->redirect('@messages_inbox');
  }

}
