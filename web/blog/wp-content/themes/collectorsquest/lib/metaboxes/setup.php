<?php

include_once WP_CONTENT_DIR . '/themes/collectorsquest/lib/wpalchemy/MetaBox.php';

// global styles for the meta boxes
if (is_admin()) wp_enqueue_style('wpalchemy-metabox', get_stylesheet_directory_uri() . '/lib/metaboxes/meta.css');

//include_once 'thumbs-spec.php';
//include_once 'homepage-carousel-spec.php';
include_once 'homepage-showcase-spec.php';
include_once 'collectorsquestion-spec.php';
include_once 'market-explore-spec.php';
include_once 'market-featured-spec.php';
include_once 'collections-explore-spec.php';
include_once 'featured-week-spec.php';
include_once 'featured-items-spec.php';
include_once 'seller-spotlight-spec.php';
include_once 'market-theme-spec.php';
include_once 'search-results-spec.php';


/* eof */
