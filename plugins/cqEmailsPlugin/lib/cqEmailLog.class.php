<?php

/**
 * cqEmailLog save sended emails to db
 */
class cqEmailLog implements Swift_Events_SendListener
{

  public function sendPerformed(Swift_Events_SendEvent $e)
  {
  }

  public function beforeSendPerformed(Swift_Events_SendEvent $e)
  {
  }

}
