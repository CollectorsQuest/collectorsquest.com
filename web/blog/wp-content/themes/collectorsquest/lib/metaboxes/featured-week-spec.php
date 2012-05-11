<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_featured_week_collectibles',
	'title' => 'Featured Week Collectibles',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/featured-week-meta.php',
  'types' => array('featured_week'),
  'context' => 'normal',
  'priority' => 'high',
));

/* eof */
