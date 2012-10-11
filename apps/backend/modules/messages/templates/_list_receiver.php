<?php
/** @var $PrivateMessage PrivateMessage */
echo  $PrivateMessage->getCollectorRelatedByReceiver()
  ? link_to_frontend(
    $PrivateMessage->getCollectorRelatedByReceiver()->getUsername(), 'collector_by_slug',
    $PrivateMessage->getCollectorRelatedByReceiver(), array('target' => '_blank')
  )
  :'';
