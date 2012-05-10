<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_thumb_options',
	'title' => 'Thumbnail Options',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/thumbs-meta.php',
  'context' => 'side',
  'priority' => 'low'
));

/* eof */
