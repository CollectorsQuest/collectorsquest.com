<?php
 /** @var $CollectorCollection CollectorCollection */

echo preg_replace('/[^(\x20-\x7F)]*/', '',
  html_entity_decode($CollectorCollection->getDescription())
);

