<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_collections_explore_items',
	'title' => 'Explore Collections',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/collections-explore-meta.php',
  'types' => array('collections_explore'),
  'context' => 'normal',
  'priority' => 'high',
));

/* eof */
