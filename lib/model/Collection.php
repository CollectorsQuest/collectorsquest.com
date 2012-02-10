<?php

class Collection extends BaseCollection
{

}

sfPropelBehavior::add('Collection', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'Collection',
  array('PropelActAsEblobBehavior' => array('column' => 'eblob')
));

sfPropelBehavior::add(
  'Collection',
  array(
    'PropelActAsSluggableBehavior' => array(
      'columns' => array(
        'from' => CollectionPeer::NAME,
        'to' => CollectionPeer::SLUG
      ),
      'separator' => '-',
      'permanent' => false,
      'lowercase' => true,
      'ascii' => true,
      'chars' => 128
    )
  )
);
