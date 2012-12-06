<?php
/** @var $Collectible Collectible */
$ShoppingCart = $ShoppingCartCollectible->getShoppingCart();
$Collector = $ShoppingCart->getCollector();

if ($Collector)
{
  echo link_to_frontend(
    $Collector, 'collector_by_slug',
    $Collector, array('target' => '_blank')
  );
}
else
{
  echo 'Guest';
}
