<?php
/** @var $PrivateMessage PrivateMessage */
echo $PrivateMessage->getCollectorRelatedBySender()
  ? link_to(
    $PrivateMessage->getCollectorRelatedBySender()->getUsername(),
    'collector_edit',
    $PrivateMessage->getCollectorRelatedBySender()
  )
  :'';
