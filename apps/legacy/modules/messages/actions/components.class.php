<?php

class messagesComponents extends cqComponents
{
  public function executeSidebar()
  {
    $this->buttons = array(
      0 => array(
        'text' => sprintf(
          'Inbox <small>(%d/%d)</small>',
          $this->getUser()->getUnreadMessagesCount(),
          $this->getUser()->getMessagesCount()
        ),
        'icon' => 'mail-closed',
        'route' => '@messages_inbox'
      ),
      1 => array(
        'text' => 'Compose Message',
        'icon' => 'plus',
        'route' => '@message_compose'
      ),
      2 => array(
        'text' => 'Sent Messages',
        'icon' => 'arrowreturnthick-1-e',
        'route' => '@messages_sent'
      )
    );

    return sfView::SUCCESS;
  }

  public function executeReply()
  {
    /** @var $message PrivateMessage */
    $message = $this->getVar('message');

    $this->form = new PrivateMessageForm();
    $this->form->setDefaults(array(
      'id' => $message->getId(),
      'sender' => $message->getSender(),
      'subject' => $message->getSubject()
    ));

    $this->message = $message;

    return sfView::SUCCESS;
  }
}
