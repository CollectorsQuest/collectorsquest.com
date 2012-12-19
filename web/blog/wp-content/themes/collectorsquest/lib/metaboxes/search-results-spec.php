<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_search_result',
	'title' => 'Search Results',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/search-results-meta.php',
  'types' => array('search_result'),
  'context' => 'normal',
  'priority' => 'high',
  'save_filter' => 'validate_collectible_ids_with_sizes'
));

/* eof */
