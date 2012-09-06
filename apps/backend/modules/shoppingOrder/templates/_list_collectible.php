<?php
/** @var $ShoppingOrder ShoppingOrder */

echo link_to($ShoppingOrder->getCollectible()->getName(), 'collectible_edit', $ShoppingOrder->getCollectible());
