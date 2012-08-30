<?php
/** @var $PrivateMessage PrivateMessage */
echo  $PrivateMessage->getCollectorRelatedByReceiver()? link_to($PrivateMessage->getCollectorRelatedByReceiver()->getUsername(), 'collector_edit', $PrivateMessage->getCollectorRelatedByReceiver()):'' ;
