<?php
/** @var $EmailsLog EmailsLog */
echo sprintf('<b>%s</b><br />(%s)', $EmailsLog->getReceiverEmail(), $EmailsLog->getReceiverName() ?: 'none');
