<?php
/** @var $SentEmail SentEmail */
echo sprintf('<b>%s</b><br />(%s)', $SentEmail->getSenderEmail(), $SentEmail->getSenderName() ?: 'none');
