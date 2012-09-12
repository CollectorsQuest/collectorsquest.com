<?php
/** @var $ShoppingOrder ShoppingOrder */

echo link_to_frontend(
  $ShoppingOrder->getCollectible()->getName(), 'collectible_by_slug',
  $ShoppingOrder->getCollectible(), array('target' => '_blank')
);
