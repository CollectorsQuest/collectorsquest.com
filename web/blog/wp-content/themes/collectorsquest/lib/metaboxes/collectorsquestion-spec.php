<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_collectors_question_details',
	'title' => 'Collectors\' Question Details',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/collectorsquestion-meta.php',
  'types' => array('collectors_question'),
  'context' => 'side',
  'priority' => 'low',
));

/* eof */
