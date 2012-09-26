<?php

/**
 * messages actions.
 *
 * @package    CollectorsQuest
 * @subpackage messages
 */
class messagesActions extends cqFrontendActions
{
  public function preExecute()
  {
    SmartMenu::setSelected('mycq_menu', 'messages');
  }

  public function executeIndex()
  {
    return $this->redirect('@messages_inbox');
  }

  public function executeInbox(sfWebRequest $request)
  {
    // possible values: all, read, unread
    $this->filter_by = $request->getParameter('filter', 'all');

    $is_search = $request->hasParameter('search');
    $search = '%'.$request->getParameter('search').'%';

    $q = PrivateMessageQuery::create()
      ->filterByCollectorRelatedByReceiver($this->getCollector())
      ->_if('read' == $this->filter_by)
        ->filterByIsRead(true)
      ->_elseif('unread' == $this->filter_by)
        ->filterByIsRead(false)
      ->_endif()
      ->filterByIsDeleted(false)
      ->_if($is_search)
        //->joinCollectorRelatedBySender(null, Criteria::LEFT_JOIN)
        ->filterBySubject($search)
        ->_or()
        ->filterByBody($search)
        ->_or()
        ->useCollectorRelatedBySenderQuery()
          ->filterByDisplayName($search)
          ->_or()
          ->filterByUsername($search)
        ->endUse()
      ->_endif()
      ->orderByCreatedAt(Criteria::DESC);

    $pager = new PropelModelPager($q);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->search = $request->getParameter('search');

    if ($request->isXmlHttpRequest())
    {
      return $this->renderPartial('inbox_table', array(
          'filter_by' => $this->filter_by,
          'pager' => $this->pager,
          'search' => $this->search,
      ));
    }

    return sfView::SUCCESS;
  }

  public function executeSent(sfWebRequest $request)
  {
    $q = PrivateMessageQuery::create()
      ->filterByCollectorRelatedBySender($this->getCollector())
      ->groupByThread()
      ->orderByCreatedAt(Criteria::DESC);

    $pager = new PropelModelPager($q);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

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
    $form->setDefault('subject', $request->getParameter('subject'));

    if ($request->hasParameter('goto'))
    {
      $form->setDefault('goto', $request->getParameter('goto'));
    }
    if ($request->hasParameter('collection_id'))
    {
      $form->setDefault('collection_id', $request->getParamteter('collection_id'));
    }
    if ($request->hasParameter('collectible_id'))
    {
      $form->setDefault('collectible_id', $request->getParamteter('collectible_id'));
    }

    // If "to" param is numeric, try to add corresponding Collector username as default
    $to = $request->getParameter('to');
    if (is_numeric($to) && $receiver = CollectorPeer::retrieveByPK($to))
    {
      $form->setDefault('receiver', $receiver->getUsername());
    }
    else
    {
      $form->setDefault('receiver', $to);
    }

    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $receiver = $form->getValue('receiver');
        $cqEmail = new cqEmail($this->getMailer());

        // if we are sending a message to an actual collector
        // and the collector wants to receive notifications
        if ($receiver instanceof Collector && $receiver->getNotificationsMessage())
        {
          // then we need to save a message object
          $message = $form->save();

          // and send it as normal
          $cqEmail->send('Messages/private_message_notification', array(
              'to' => $receiver->getEmail(),
              'params' => array(
                'oSender' => $sender,
                'oReceiver' => $receiver,
                'oMessage' => $message,
                'sThreadUrl' => $this->generateUrl('messages_show', $message, true)
                                . '#latest-message',
              ),
          ));
        }
        else
        {
          // so we just notify the recepient and set the reply-to header to
          // the sender's email
          $cqEmail->send('Messages/relay_message_to_unregistered_user', array(
              'to' => $receiver,
              'replyTo' => $sender->getEmail(),
              'params' => array(
                'oSender' => $sender,
                'sMessageBody' => $form->getValue('body'),
              ),
          ));
        }

        // if the sender opted for receiving a copy of the message
        if ($form->getValue('copy_for_sender'))
        {
          $cqEmail->send('Messages/private_message_copy_for_sender', array(
              'to' => $sender->getEmail(),
              'params' => array(
                'oSender' => $sender,
                'sReceiver' => (string) $receiver,
                'sMessageBody' => $form->getValue('body'),
              ),
          ));
        }

        if ($form->getValue('thread'))
        {
          // we are replaying to a thread, so we should be redirected
          // to the thread's page
          return $this->redirect($this->generateUrl('messages_show', $form->getObject()).'#latest-message');
        }
        else
        {
          // we are starting a new thread, so redirect to inbox with a success flash
          $this->getUser()->setFlash('success', sprintf(
            'Your message has been sent to %s.',
            $receiver instanceof Collector
              ? $receiver->getDisplayName()
              : $receiver
          ));

          return $this->redirect($form->getValue('goto') ?: 'messages_inbox');
        }
      }
      else
      {
        // Set the error message
        $this->getUser()->setFlash(
          'error', $this->__('There is a problem with sending your message.')
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
    elseif (isset($action['mark_as_read']))
    {
      $q->update(array('IsRead' => true));
    }
    elseif (isset($action['delete']))
    {
      $q->update(array('IsDeleted' => true));
    }

    if ($request->isXmlHttpRequest())
    {
      // if ajax request, simply forward the rendering to messages/inbox
      return $this->forward('messages', 'inbox');
    }
    else
    {
      // if a normal request, do a proper redirect to the inbox
      return $this->redirect('messages_inbox', array(
          'filter' => $request->getParameter('filter_hidden') ?: null,
          'search' => $request->getParameter('search') ?: null,
          ));
    }
  }

}
