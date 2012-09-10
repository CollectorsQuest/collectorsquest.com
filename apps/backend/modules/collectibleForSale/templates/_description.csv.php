<?php
/* @var $CollectibleForSale CollectibleForSale */

echo preg_replace('/[^(\x20-\x7F)]*/', '',
  html_entity_decode($CollectibleForSale->getDescription('stripped'))
);

