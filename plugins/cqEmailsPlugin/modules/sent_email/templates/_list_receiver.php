<?php
/** @var $SentEmail SentEmail */
echo sprintf('<b>%s</b><br />(%s)', $SentEmail->getReceiverEmail(), $SentEmail->getReceiverName() ?: 'none');
