<?php

/**
 * @method  cqFrontendUser  getUser()
 */
class ajaxAction extends cqAjaxAction
{
  public function getObject(sfRequest $request)
  {
    return null;
  }

  public function executeMessageSend(sfWebRequest $request, $template = null)
  {
    $form = new ComposePrivateMessageForm($sender = $this->getUser()->getCollector(), $this->getUser());
    $form->setDefault('subject', $request->getParameter('subject'));

    // hide the receiver filed from the form
    $form->setWidget('receiver', new sfWidgetFormInputHidden());

    // If "to" param is numeric, try to add corresponding Collector username as default
    $to = $request->getParameter('to');
    if (is_numeric($to) && ($receiver = CollectorPeer::retrieveByPK($to)))
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

        // then we need to save a message object
        $message = $form->save();

        // if we are sending a message to an actual collector
        // and the collector wants to receive notifications
        if ($receiver instanceof Collector && $receiver->getNotificationsMessage())
        {
          // and send it as normal
         $cqEmail->send('Messages/private_message_notification', array(
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
        else if (filter_var($receiver, FILTER_VALIDATE_EMAIL))
        {
          // so we just notify the recepient and set the reply-to header to
          // the sender's email
          $cqEmail->send('Messages/relay_message_to_unregistered_user', array(
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

        if ($message)
        {
          $this->getUser()->setFlash(
            'success_ajax',
            sprintf(
              'Your message has been sent to %s.',
              $receiver instanceof Collector
                ? $receiver->getDisplayName()
                : $receiver
            )
          );
        }
        else
        {
          $this->getUser()->setFlash('error', 'There are errors in the fields or some are left empty.');
        }
      }
      else
      {
        // Set the error message
        $this->getUser()->setFlash('error', 'There is a problem with sending your message.');
      }

      return $this->redirect($form->getValue('goto') ?: 'shopping_cart');
    }

    $this->form = $form;

    return $template;
  }

}
