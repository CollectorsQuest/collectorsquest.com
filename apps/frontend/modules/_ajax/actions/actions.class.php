<?php

class _ajaxActions extends cqFrontendActions
{
  public function executeEditable(sfWebRequest $request)
  {
    @list($model, $id, $field) = explode('_', $request->getParameter('id'));
    $value = $request->getParameter('value');

    if ($model && is_callable(array(sfInflector::camelize($model).'Peer', 'retrieveByPk')))
    {
      // Cast the id to INT for sanity
      $id = (int) $id;

      // Retrieve the Object from the database
      $object = call_user_func_array(
        array(sfInflector::camelize($model).'Peer', 'retrieveByPk'), array($id)
      );

      if ($object && is_callable(array($object, 'set'. sfInflector::camelize($field))))
      {
        if ($this->getUser()->isOwnerOf($object))
        {
          call_user_func_array(array($object, 'set'. sfInflector::camelize($field)), array($value));
          $object->save();
        }
      }

      if (is_callable(array($object, 'get'. sfInflector::camelize($field))))
      {
        $value = call_user_func(array($object, 'get'. sfInflector::camelize($field)));
      }
    }

    $this->renderText($value);

    return sfView::NONE;
  }

  public function executeEditableLoad(sfWebRequest $request)
  {
    @list($model, $id, $field) = explode('_', $request->getParameter('id'));
    $value = '';

    if ($model && is_callable(array(sfInflector::camelize($model).'Peer', 'retrieveByPk')))
    {
      // Cast the id to INT for sanity
      $id = (int) $id;

      // Retrieve the Object from the database
      $object = call_user_func_array(
        array(sfInflector::camelize($model).'Peer', 'retrieveByPk'), array($id)
      );

      if (is_callable(array($object, 'get'. sfInflector::camelize($field))))
      {
        $value = call_user_func(array($object, 'get'. sfInflector::camelize($field)));
      }
    }

    $this->renderText($value);

    return sfView::NONE;
  }

  public function executeFeedback(sfWebRequest $request)
  {
    $this->form = new FeedbackForm();
    $this->form->setDefault('page', $request->getParameter('page', $request->getReferer()));

    if ($this->getUser()->isAuthenticated())
    {
      $this->form->setDefault('fullname', $this->getCollector()->getUsername());
      $this->form->setDefault('email', $this->getCollector()->getEmail());
    }

    if ($request->isMethod('post'))
    {
      $sent = false;

      $this->form->bind($request->getParameter('feedback'));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();

        $cqEmail = new cqEmail($this->getMailer());
        $sent = $cqEmail->send('internal/feedback', array(
            'to' => 'info@collectorsquest.com',
            'subject' => '[Feedback] '. $values['fullname'],
            'params' => array(
              'feedback' => array(
                'fullname' => $values['fullname'],
                'email' => $values['email'],
                'message' => nl2br($values['message']),
                'page' => urldecode($values['page'])
              ),
              'browser' => array(
                'f_ip_address' => cqStatic::getUserIpAddress(),
                'f_javascript_enabled' => $values['f_javascript_enabled'],
                'f_browser_type' => $values['f_browser_type'],
                'f_browser_version' => $values['f_browser_version'],
                'f_browser_color_depth' => $values['f_browser_color_depth'],
                'f_resolution' => $values['f_resolution'],
                'f_browser_size' => $values['f_browser_size']
              ),
            ),
        ));

        if ($values['send_copy'])
        {
          $cqEmail = new cqEmail($this->getMailer());
          $sent = $cqEmail->send('internal/feedback_copy', array(
              'to' => $values['email'],
              'subject' => '[Feedback] '. $values['fullname'],
              'params' => array(
                'feedback' => array(
                'fullname' => $values['fullname'],
                'email' => $values['email'],
                'message' => nl2br($values['message']),
                'page' => urldecode($values['page'])
              ),
            ),
          ));
        }
      }

      if ($sent)
      {
        $this->getUser()->setFlash(
          'success_ajax',
          $this->__(
            'Thank you for the feedback. If needed, we will get in
             touch with you within the next business day.',
            array(), 'flash'
          )
        );

        $this->setTemplate('successFeedback');
      }
      else
      {
        $this->getUser()->setFlash(
          'error',
          $this->__('There are errors in the fields or some are left empty.', array(), 'flash')
        );
      }
    }
  }

  public function executeSendPm(sfWebRequest $request)
  {
    $form = new ComposePrivateMessageForm($sender = $this->getCollector(), $this->getUser());
    $form->setDefault('subject', $request->getParameter('subject'));

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

    $this->item = $request->getParameter('item');
    $subject = 'Question about item ' . $this->item;

    $form->setDefault('subject', $subject);

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
          $sent = $cqEmail->send('Messages/private_message_notification', array(
            'to' => $receiver->getEmail(),
            'subject' => $subject,
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
          $sent = $cqEmail->send('Messages/relay_message_to_unregistered_user', array(
            'to' => $receiver,
            'subject' => $subject,
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
            'subject' => $subject,
            'params' => array(
              'oSender' => $sender,
              'sReceiver' => (string) $receiver,
              'sMessageBody' => $form->getValue('body'),
            ),
          ));
        }

        if ($sent)
        {
          $this->getUser()->setFlash(
            'success_ajax',
            $this->__(
              sprintf(
                'Your message has been sent to %s.',
                $receiver instanceof Collector
                  ? $receiver->getDisplayName()
                  : $receiver
              ),
             array(), 'flash'
            )
          );

          $this->setTemplate('successFeedback');
        }
        else
        {
          $this->getUser()->setFlash(
            'error',
            $this->__('There are errors in the fields or some are left empty.', array(), 'flash')
          );
        }

        return $this->redirect($form->getValue('goto') ?: 'shopping_cart');
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


  public function executeMultimedia(sfWebRequest $request)
  {
    if (!$mutlimedia = iceModelMultimediaQuery::create()->findOneById($request->getParameter('id')))
    {
      return sfView::NONE;
    }

    $this->multimedia = $mutlimedia;
    $this->which = $request->getParameter('which', 'original');

    return sfView::SUCCESS;
  }

}
