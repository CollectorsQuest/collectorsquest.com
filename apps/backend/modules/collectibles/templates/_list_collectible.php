<?php
/** @var $Collectible Collectible */

echo link_to_frontend(
  $Collectible->getName(), 'collectible_by_slug',
  $Collectible, array('target' => '_blank')
);
