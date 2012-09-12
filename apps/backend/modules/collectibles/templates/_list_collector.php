<?php
/** @var $Collectible Collectible */

echo link_to_frontend(
  $Collectible->getCollector(), 'collector_by_slug',
  $Collectible->getCollector(), array('target' => '_blank')
);
