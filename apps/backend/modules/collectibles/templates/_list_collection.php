<?php
/** @var $Collectible Collectible */

echo link_to_frontend($Collectible->getCollection(), 'collection_by_slug',
  $Collectible->getCollection(), array('target' => '_blank')
);
