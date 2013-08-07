<?php

$custom_video_url = new WPAlchemy_MetaBox(array
(
  'id' => '_video_url',
  'title' => 'Video',
  'template' => get_stylesheet_directory() . '/lib/metaboxes/video-url-meta.php',
  'context' => 'normal',
  'priority' => 'high',
  'types' => array('video'),
  'mode' => WPALCHEMY_MODE_EXTRACT,
  'prefix' => '_cq_'
));

/* eof */