<?php
/* @var $Collector Collector */

echo link_to_frontend(
  $Collector->getUsername(), 'collector_by_slug',
  $Collector, array('target' => '_blank')
);
