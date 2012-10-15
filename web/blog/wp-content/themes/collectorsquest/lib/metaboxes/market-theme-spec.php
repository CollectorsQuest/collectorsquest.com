<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_market_theme',
	'title' => 'Custom IDs or other fileds',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/market-theme-meta.php',
  'types' => array('market_theme'),
  'context' => 'normal',
  'priority' => 'high',
));

/* eof */
