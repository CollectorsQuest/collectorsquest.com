<?php

if ( function_exists('register_sidebar') ) :
  register_sidebar(array(
    'name' => __( 'Blog Sidebar' ),
    'id' => 'non-singular-sidebar',
    'description' => __( 'Widgets in this area will be shown on the right side of non-singular blog archives.' ),
    'before_title' => '<h1>',
    'after_title' => '</h1>'
  ));

  register_sidebar(array(
    'name' => __( 'Blog Post Sidebar' ),
    'id' => 'singular-sidebar',
    'description' => __( 'Widgets in this area will be shown on the right side of singular blog posts.' ),
    'before_title' => '<h1>',
    'after_title' => '</h1>'
  ));

  register_sidebar(array(
    'name' => __( 'Static Page Sidebar' ),
    'id' => 'static-page-sidebar',
    'description' => __( 'Widgets in this area will be shown on the right side of static pages.' ),
    'before_title' => '<h1>',
    'after_title' => '</h1>'
  ));
endif;


require_once 'widget-300x250ad.php';
require_once 'widget-tags.php';
require_once 'widget-our-bloggers.php';
require_once 'widget-other-news.php';

?>
