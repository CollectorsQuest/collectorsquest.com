<?php

/**
 * messages actions.
 *
 * @package    CollectorsQuest
 * @subpackage messages
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class messagesActions extends cqActions
{
  public function preExecute()
  {
    parent::preExecute();

    /**
     * This variable is for us to know which box to make active in the navigation
     */
    $this->getRequest()->setAttribute('header_icons_active', 'messages');
  }

 /**
  * Executes index action
  *
  * @param  sfWebRequest  $request  A request object
  * @return string
  */
  public function executeIndex(sfWebRequest $request)
  {
    return $this->forward('messages', 'inbox');
  }

  public function executeInbox(sfWebRequest $request)
  {
    $collector = $this->getUser()->getCollector();
    $this->forward404Unless($collector);

    $c = new Criteria();
    $c->add(PrivateMessagePeer::RECEIVER, $collector->getId());
    $c->add(PrivateMessagePeer::IS_DELETED, false);
    $c->addGroupByColumn(PrivateMessagePeer::THREAD);
    $c->addDescendingOrderByColumn(PrivateMessagePeer::CREATED_AT);

    switch($request->getParameter('show'))
    {
      case 'unread':
        $c->add(PrivateMessagePeer::IS_READ, false);
        break;
      case 'read':
        $c->add(PrivateMessagePeer::IS_READ, true);
        break;
      case 'all':
      default:
        // nothing to do here
        break;
    }

    $this->messages = PrivateMessagePeer::doSelect($c);

    $this->addBreadcrumb($this->__('Messages'), '@messages_inbox');
    $this->addBreadcrumb(sprintf(
      $this->__('Inbox <small>(%d/%d)</small>'), $collector->getUnreadMessagesCount(), $collector->getMessagesCount()
    ));

    return sfView::SUCCESS;
  }

  public function executeSent(sfWebRequest $request)
  {
    $collector = $this->getUser()->getCollector();
    $this->forward404Unless($collector);

    $c = new Criteria();
    $c->add(PrivateMessagePeer::SENDER, $collector->getId());
    $c->add(PrivateMessagePeer::IS_DELETED, false);
    $c->addGroupByColumn(PrivateMessagePeer::THREAD);
    $c->addDescendingOrderByColumn(PrivateMessagePeer::CREATED_AT);

    $this->messages = PrivateMessagePeer::doSelect($c);

    $this->addBreadcrumb('Messages', '@messages_inbox');
    $this->addBreadcrumb('Sent');

    return sfView::SUCCESS;
  }

  public function executeShow(sfWebRequest $request)
  {
    $c = new Criteria();
    $c->add(PrivateMessagePeer::ID, $request->getParameter('id'));
    $message = PrivateMessagePeer::doSelectOne($c);

    // Forward to a 404 page unless we have both the message and the current user is involved in the message thread
    $this->forward404Unless(
      $message && in_array($this->getUser()->getId(), array($message->getSender(), $message->getReceiver()))
    );

    if (!$this->getUser()->isOwnerOf($message))
    {
      $message->setIsRead(true);
      $message->save();
    }

    if ($message->getThread())
    {
      $c = new Criteria();
      $c->add(PrivateMessagePeer::THREAD, $message->getThread());
      $c->addAscendingOrderByColumn(PrivateMessagePeer::CREATED_AT);

      $messages = PrivateMessagePeer::doSelect($c);

      // Make sure we mark all messages in the thread as read
      foreach($messages as $m)
      {
        if (!$this->getUser()->isOwnerOf($m))
        {
          $m->setIsRead(true);
          $m->save();
        }
      }
    }
    else
    {
      $message->setThread(PrivateMessagePeer::generateThread());
      $message->save();

      $messages = array($this->message);
    }

    $this->message  = $message;
    $this->messages = $messages;

    $this->addBreadcrumb('Messages', '@messages_inbox');
    $this->addBreadcrumb($message->getSubject());

    return sfView::SUCCESS;
  }

  public function executeCompose(sfWebRequest $request)
  {
    $post = $request->getParameter('message');
    $message = PrivateMessagePeer::retrieveByPK($post['id']);

    // A shortcut for clarity
    $sender = $this->getCollector();

    $form = new PrivateMessageForm($message);
    $form->setDefaults(array(
      'sender' => $sender->getId(),
      'subject' => $request->getParameter('subject'))
    );

    if ($request->isMethod('post'))
    {
      $form->bind($request->getParameter('message'));
      if ($form->isValid())
      {
        $receivers = array();
        $collectors = CollectorPeer::retrieveByPKs($form->getValue('receiver'));

        /** @var $receiver Collector */
        foreach ($collectors as $receiver)
        {
          $options = array(
            'thread' => ($message instanceof PrivateMessage) ? $message->getThread() : null,
            'subject' => $form->getValue('subject'),
            'body' => $form->getValue('body')
          );

          // Send the PrivateMessage and email notification
          if ($message = PrivateMessagePeer::send($receiver, $sender, $options))
          {
            if ($email = $receiver->getEmail())
            {
              $subject = $this->__('You have a new message from %sender%', array('%sender%' => $sender->getDisplayName()));
              $body = $this->getPartial(
                'emails/private_message_notification',
                array('sender' => $sender, 'receiver' => $receiver, 'message' => $message)
              );
              $this->sendEmail($email, $subject, $body);
            }

            $receivers[] = $receiver->getDisplayName();
          }
        }

        // Set the error message
        $this->getUser()->setFlash(
          "success", sprintf($this->__('Your message to %s has been sent!'), '<b>'. implode('</b>, <b>', $receivers) .'</b>')
        );

        if (($message instanceof PrivateMessage))
        {
          return $this->redirect('@message_show?id='. $message->getId());
        }
        else
        {
          return $this->redirect('@messages_inbox');
        }
      }
      else
      {
        $pks = $request->getParameter('message[receiver]');

        // Set the error message
        $this->getUser()->setFlash(
          "error", $this->__("There is a problem with sending your message.")
        );
      }
    }
    else
    {
      $pks = explode(';', $request->getParameter('to'));
    }

    $this->receivers = array();
    $this->form = $form;

    if (!empty($pks))
    {
      $c = new Criteria();
      $c->addSelectColumn(CollectorPeer::ID);
      $c->addSelectColumn(CollectorPeer::USERNAME);
      $c->addSelectColumn(CollectorPeer::DISPLAY_NAME);
      $c->add(CollectorPeer::ID, $pks, Criteria::IN);

      $stmt = CollectorPeer::doSelectStmt($c);
      while ($row = $stmt->fetch(PDO::FETCH_NUM))
      {
        $this->receivers[] = array('caption' => sprintf('%s (%s)', $row[2], $row[1]), 'value' => $row[0]);
      }
    }

    $this->addBreadcrumb('Messages', '@messages_inbox');
    $this->addBreadcrumb('Compose');

    return sfView::SUCCESS;
  }

  public function executeBatchActions(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      $c = new Criteria();
      $c->add(PrivateMessagePeer::THREAD, $request->getParameter('message[thread]'), Criteria::IN);
      $c->add(PrivateMessagePeer::IS_DELETED, false);
      $c->add(PrivateMessagePeer::RECEIVER, $this->getUser()->getId());

      $messages = PrivateMessagePeer::doSelect($c);
      $total = count($request->getParameter('message[thread]'));

      switch (true)
      {
        case $request->getParameter('cmd[delete]', null) !== null:
          foreach ($messages as $message)
          {
            $message->setIsDeleted(true);
            $message->save();
          }

          $this->getUser()->setFlash(
            "success", sprintf($this->__('You just deleted %d of your inbox messages!'), $total)
          );
          break;
        case $request->getParameter('cmd[unread]', null) !== null:
          foreach ($messages as $message)
          {
            $message->setIsRead(false);
            $message->save();
          }

          $this->getUser()->setFlash(
            "success", sprintf($this->__('You just marked %d of your inbox messages as unread!'), $total)
          );
          break;
        case $request->getParameter('cmd[read]', null) !== null:
          foreach ($messages as $message)
          {
            $message->setIsRead(true);
            $message->save();
          }

          $this->getUser()->setFlash(
            "success", sprintf($this->__('You just marked %d of your inbox messages as read!'), $total)
          );
          break;
      }
    }

    return $this->redirect('@messages_inbox');
  }
}
