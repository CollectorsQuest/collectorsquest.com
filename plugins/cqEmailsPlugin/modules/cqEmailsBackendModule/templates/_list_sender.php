<?php
/** @var $EmailsLog EmailsLog */
echo sprintf('<b>%s</b><br />(%s)', $EmailsLog->getSenderEmail(), $EmailsLog->getSenderName() ?: 'none');
