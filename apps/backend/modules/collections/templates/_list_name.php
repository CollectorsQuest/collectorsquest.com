<?php
/** @var $CollectorCollection CollectorCollection */

echo link_to_frontend($CollectorCollection->getName(), 'collection_by_slug',
  $CollectorCollection, array('target' => '_blank'));
