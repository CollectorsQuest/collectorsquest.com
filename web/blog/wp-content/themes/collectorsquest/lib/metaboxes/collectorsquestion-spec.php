<?php

$custom_thumbs_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_collectors_question_info',
	'title' => 'Collectors\' Question Info',
	'template' => get_stylesheet_directory() . '/lib/metaboxes/collectorsquestion-meta.php',
  'types' => array('collectors_question'),
  'context' => 'normal',
  'priority' => 'high',
));

/* eof */
