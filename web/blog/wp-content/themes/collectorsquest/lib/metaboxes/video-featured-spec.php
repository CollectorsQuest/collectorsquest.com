<?php

$custom_video_reatured = new WPAlchemy_MetaBox(array
(
  'id' => '_featured',
  'title' => 'Featured Video',
  'template' => get_stylesheet_directory() . '/lib/metaboxes/video-featured-meta.php',
  'context' =>  'side',
  'priority' => 'high',
  'types' => array('video'),
  'mode' => WPALCHEMY_MODE_EXTRACT,
  'prefix' => '_cq_'
));

/* eof */