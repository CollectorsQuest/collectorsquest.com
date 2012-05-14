<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_seller_spotlight',
	'title' => 'Featured Seller IDs',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/seller-spotlight-meta.php',
  'types' => array('seller_spotlight'),
  'context' => 'normal',
  'priority' => 'high',
));

/* eof */
