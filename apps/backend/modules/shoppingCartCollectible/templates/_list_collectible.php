<?php
/* @var $Collectible Collectible */
$Collectible = $ShoppingCartCollectible->getCollectible();

if ($Collectible)
{
  echo link_to_frontend(
    $Collectible->getName(), 'collectible_by_slug',
    $Collectible, array('target' => '_blank')
  );
}
else
{
  echo 'n/a';
}
