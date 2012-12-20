<?php
/* @var $PrivateMessage PrivateMessage */
echo $PrivateMessage->getCollectorRelatedBySenderId()
  ? link_to_frontend(
    $PrivateMessage->getCollectorRelatedBySenderId()->getUsername(),
    'collector_by_slug',
    $PrivateMessage->getCollectorRelatedBySenderId(),
    array('target' => '_blank')
  )
  : '';
