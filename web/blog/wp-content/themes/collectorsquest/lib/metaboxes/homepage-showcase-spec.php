<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_homepage_showcase_items',
	'title' => 'Homepage Showcase Items',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/homepage-showcase-meta.php',
  'types' => array('homepage_showcase'),
  'context' => 'normal',
  'priority' => 'high',
));

/* eof */
