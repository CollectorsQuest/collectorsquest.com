<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_featured_items',
	'title' => 'Featured Collectibles',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/featured-items-meta.php',
  'types' => array('featured_items'),
  'context' => 'normal',
  'priority' => 'high',
  'save_filter' => 'validate_collectible_ids_with_sizes'
));

/* eof */
