<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_market_explore_items',
	'title' => 'Explore Items from the Market',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/market-explore-meta.php',
  'types' => array('marketplace_explore'),
  'context' => 'normal',
  'priority' => 'high',
));

/* eof */
