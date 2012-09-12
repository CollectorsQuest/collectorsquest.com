<?php
/** @var $CollectorCollection CollectorCollection */

echo link_to_frontend(
  $CollectorCollection->getCollector(), 'collector_by_slug',
  $CollectorCollection->getCollector(), array('target' => '_blank')
);
