<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_market_featured_items',
	'title' => 'Featured Items from the Market',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/market-featured-meta.php',
  'types' => array('marketplace_featured'),
  'context' => 'normal',
  'priority' => 'high',
));

/* eof */
