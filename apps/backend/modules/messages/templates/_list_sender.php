<?php
/** @var $PrivateMessage PrivateMessage */
echo $PrivateMessage->getCollectorRelatedBySender()
  ? link_to_frontend(
    $PrivateMessage->getCollectorRelatedBySender()->getUsername(), 'collector_by_slug',
    $PrivateMessage->getCollectorRelatedBySender(), array('target' => '_blank')
  )
  :'';
