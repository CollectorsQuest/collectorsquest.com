<?php
/* @var $ShoppingCartCollectible ShoppingCartCollectible */
$reference = $ShoppingCartCollectible->getShippingReference($ShoppingCartCollectible->getShippingCountryIso3166());

if ($reference->isSimpleFreeShipping())
{
  echo 'Free shipping';
}
else
{
  echo money_format('%.2n', $reference->getSimpleShippingAmount());
}
