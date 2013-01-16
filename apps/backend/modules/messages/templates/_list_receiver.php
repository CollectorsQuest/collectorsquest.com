<?php
/* @var $PrivateMessage PrivateMessage */
echo  $PrivateMessage->getCollectorRelatedByReceiverId()
  ? link_to_frontend(
    $PrivateMessage->getCollectorRelatedByReceiverId()->getUsername(),
    'collector_by_slug',
    $PrivateMessage->getCollectorRelatedByReceiverId(),
    array('target' => '_blank')
  )
  : $PrivateMessage->getReceiverEmail();
