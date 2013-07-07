<?php

/** http://codex.wordpress.org/Post_Thumbnails */
add_theme_support('post-thumbnails');

/** Adding custom image size for the site's homepage (not the blog homepage) */
add_image_size('homepage', 270, 270, true);

/** Adding custom image size for the blog's homepage */
add_image_size('blog-homepage-p1', 300, 300, true);

// Custon taxonomy for blog posts matching
register_taxonomy(
  'matching', 'post',
  array(
    'hierarchical' => false, 'label' => 'Matching',
    'query_var' =>  true, 'rewrite' => true
  )
);

// Custon taxonomy for video playlist
register_taxonomy(
  'playlist', 'video',
  array(
    'hierarchical' => true, 'labels' => array(
    'name'                       => _x( 'Playlists', 'taxonomy general name' ),
    'singular_name'              => _x( 'Playlist', 'taxonomy singular name' ),
    'search_items'               => __( 'Search Playlists' ),
    'popular_items'              => __( 'Popular Playlists' ),
    'all_items'                  => __( 'All Playlists' ),
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => __( 'Edit Playlist' ),
    'update_item'                => __( 'Update Playlist' ),
    'add_new_item'               => __( 'Add New Playlist' ),
    'new_item_name'              => __( 'New Playlist Name' ),
    'separate_items_with_commas' => __( 'Separate playlists with commas' ),
    'add_or_remove_items'        => __( 'Add or remove playlists' ),
    'choose_from_most_used'      => __( 'Choose from the most used playlists' ),
    'not_found'                  => __( 'No playlists found.' ),
    'menu_name'                  => __( 'Playlists' ),
  )
  )
);
// Custon taxonomy for video tags
register_taxonomy(
  'video_tag', 'video',
  array(
    'hierarchical' => false, 'label' => 'Tags'
  )
);

/**
 * @see http://www.kanasolution.com/2011/01/session-variable-in-wordpress/
 */
add_action('init', 'cq_init_session', 1);
function cq_init_session()
{
  include_once __DIR__ .'/../../../../../lib/vendor/symfony/symfony1/lib/storage/sfStorage.class.php';
  include_once __DIR__ .'/../../../../../lib/vendor/symfony/symfony1/lib/storage/sfSessionStorage.class.php';
  include_once __DIR__ .'/../../../../../lib/collectorsquest/cqSessionStorage.class.php';

  $options = array('session_name' => 'cq_frontend', 'auto_start' => true);
  $session = new cqSessionStorage($options);
  $session->initialize(array());
}

/**
 * @see http://blurback.com/post/1479456356/permissions-with-wordpress-custom-post-types
 */
add_action('init', 'cq_custom_post_type_init');
function cq_custom_post_type_init()
{
  $capabilities = array(
    'publish_posts'          => 'publish_editorials',
    'read_post'              => 'read_editorial',
    'read_private_posts'     => 'read_private_editorials',

    'edit_post'              => 'edit_editorial',
    'edit_posts'             => 'edit_editorials',
    'edit_others_posts'      => 'edit_others_editorials',
    'edit_private_posts'     => 'edit_private_editorials',
    'edit_published_posts'   => 'edit_published_editorials',

    'delete_post'            => 'delete_editorial',
    'delete_posts'           => 'delete_editorials',
    'delete_others_posts'    => 'delete_others_editorials',
    'delete_private_posts'   => 'delete_private_editorials',
    'delete_published_posts' => 'delete_published_editorials',
  );

  // Fire this during init
  register_post_type('cms_slot', array(
    'label'           => __('CMS Slots'),
    'singular_label'  => __('Slot'),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'excerpt', 'editor', 'revisions')
  ));

  register_post_type('homepage_carousel', array(
    'labels' => array(
      'name'               => _x('Homepage Carousel', 'post type general name'),
      'singular_name'      => _x('Carousel', 'post type singular name'),
      'add_new'            => _x('Add New', 'Carousel'),
      'add_new_item'       => __('Add New Carousel'),
      'edit_item'          => __('Edit Carousel'),
      'new_item'           => __('New Carousel'),
      'view_item'          => __('View Carousel'),
      'search_items'       => __('Search Carousels'),
      'not_found'          => __('No Carousels found'),
      'not_found_in_trash' => __('No Carousels found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'editor', 'thumbnail', 'revisions')
  ));

  register_post_type('homepage_showcase', array(
    'labels' => array(
      'name'               => _x('Homepage Showcase', 'post type general name'),
      'singular_name'      => _x('Showcase', 'post type singular name'),
      'add_new'            => _x('Add New', 'Showcase'),
      'add_new_item'       => __('Add New Showcase'),
      'edit_item'          => __('Edit Showcase'),
      'new_item'           => __('New Showcase'),
      'view_item'          => __('View Showcase'),
      'search_items'       => __('Search Showcases'),
      'not_found'          => __('No Showcases found'),
      'not_found_in_trash' => __('No Showcases found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'custom-fields', 'revisions')
  ));

  register_post_type('collectors_question', array(
    'labels' => array(
      'name'               => _x('Collectors\' Question', 'post type general name'),
      'singular_name'      => _x('Question', 'post type singular name'),
      'add_new'            => _x('Add New', 'Question'),
      'add_new_item'       => __('Add New Question'),
      'edit_item'          => __('Edit Question'),
      'new_item'           => __('New Question'),
      'view_item'          => __('View Question'),
      'search_items'       => __('Search Questions'),
      'not_found'          => __('No Questions found'),
      'not_found_in_trash' => __('No Questions found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'editor', 'custom-fields', 'thumbnail', 'revisions')
  ));

  register_post_type('marketplace_explore', array(
    'labels' => array(
      'name'               => _x('Marketplace Explore', 'post type general name'),
      'singular_name'      => _x('Collectibles', 'post type singular name'),
      'add_new'            => _x('Add New', 'Collectible'),
      'add_new_item'       => __('Add New Collectible'),
      'edit_item'          => __('Edit Collectible'),
      'new_item'           => __('New Collectible'),
      'view_item'          => __('View Collectibles'),
      'search_items'       => __('Search Collectibles'),
      'not_found'          => __('No Collectibles found'),
      'not_found_in_trash' => __('No Collectibles found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'custom-fields', 'revisions')
  ));

  register_post_type('marketplace_featured', array(
    'labels' => array(
      'name'               => _x('Marketplace Featured', 'post type general name'),
      'singular_name'      => _x('Marketplace Featured', 'post type singular name'),
      'add_new'            => _x('Add New', 'Featured Collectibles for Sale'),
      'add_new_item'       => __('Add New Featured Collectibles for Sale'),
      'edit_item'          => __('Edit Featured Collectibles for Sale'),
      'new_item'           => __('New Featured Collectibles for Sale'),
      'view_item'          => __('View Featured Collectibles for Sale'),
      'search_items'       => __('Search Marketplace Featured'),
      'not_found'          => __('No Marketplace Featured found'),
      'not_found_in_trash' => __('No Marketplace Featured found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title','custom-fields', 'revisions')
  ));

  register_post_type('collections_explore', array(
    'labels' => array(
      'name'               => _x('Collections Explore', 'post type general name'),
      'singular_name'      => _x('Collections', 'post type singular name'),
      'add_new'            => _x('Add New', 'Collection'),
      'add_new_item'       => __('Add New Collection'),
      'edit_item'          => __('Edit Collection'),
      'new_item'           => __('New Collection'),
      'view_item'          => __('View Collection'),
      'search_items'       => __('Search Collections'),
      'not_found'          => __('No Collections found'),
      'not_found_in_trash' => __('No Collections found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'revisions')
  ));

  register_post_type('featured_week', array(
    'labels' => array(
      'name'               => _x('Featured Week', 'post type general name'),
      'singular_name'      => _x('Featured Week', 'post type singular name'),
      'add_new'            => _x('Add New', 'Featured Week'),
      'add_new_item'       => __('Add New Featured Week'),
      'edit_item'          => __('Edit Featured Week'),
      'new_item'           => __('New Featured Week'),
      'view_item'          => __('View Featured Week'),
      'search_items'       => __('Search Featured Weeks'),
      'not_found'          => __('No Featured Weeks found'),
      'not_found_in_trash' => __('No Featured Weeks found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'editor', 'thumbnail', 'revisions')
  ));

  register_post_type('featured_items', array(
    'labels' => array(
      'name'               => _x('Featured Collectibles', 'post type general name'),
      'singular_name'      => _x('Featured Collectibles', 'post type singular name'),
      'add_new'            => _x('Add New', 'Featured Collectibles'),
      'add_new_item'       => __('Add New Featured Collectibles'),
      'edit_item'          => __('Edit Featured Collectibles'),
      'new_item'           => __('New Featured Collectibles'),
      'view_item'          => __('View Featured Collectibles'),
      'search_items'       => __('Search Featured Collectibles'),
      'not_found'          => __('No Featured Collectibles found'),
      'not_found_in_trash' => __('No Featured Collectibles found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'taxonomies'      => array('post_tag'),
    'supports'        => array('title', 'editor', 'excerpt', 'tags', 'thumbnail', 'revisions')
  ));

  register_post_type('seller_spotlight', array(
    'labels' => array(
      'name'               => _x('Seller Spotlight', 'post type general name'),
      'singular_name'      => _x('Featured Seller', 'post type singular name'),
      'add_new'            => _x('Add New', 'Featured Seller'),
      'add_new_item'       => __('Add New Featured Seller'),
      'edit_item'          => __('Edit Featured Seller'),
      'new_item'           => __('New Featured Seller'),
      'view_item'          => __('View Featured Seller'),
      'search_items'       => __('Search Featured Seller'),
      'not_found'          => __('No Featured Sellers found'),
      'not_found_in_trash' => __('No Featured Sellers found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'revisions')
  ));

  register_post_type('market_theme', array(
    'labels' => array(
      'name'               => _x('Market Themes', 'post type general name'),
      'singular_name'      => _x('Market Theme', 'post type singular name'),
      'add_new'            => _x('Add New', 'Market Theme'),
      'add_new_item'       => __('Add New Market Theme'),
      'edit_item'          => __('Edit Market Theme'),
      'new_item'           => __('New Market Theme'),
      'view_item'          => __('View Market Theme'),
      'search_items'       => __('Search Market Theme'),
      'not_found'          => __('No Market Themes found'),
      'not_found_in_trash' => __('No Market Themes found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'taxonomies'      => array('post_tag'),
    'supports'        => array('title', 'editor', 'tags', 'thumbnail')
  ));

  register_post_type('search_result', array(
    'labels' => array(
      'name'               => _x('Search Results', 'post type general name'),
      'singular_name'      => _x('Search Result', 'post type singular name'),
      'add_new'            => _x('Add New', 'Search Result'),
      'add_new_item'       => __('Add New Search Result'),
      'edit_item'          => __('Edit Search Result'),
      'new_item'           => __('New Search Result'),
      'view_item'          => __('View Search Result'),
      'search_items'       => __('Search Search Results'),
      'not_found'          => __('No Search Result found'),
      'not_found_in_trash' => __('No Search Result found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array( 'slug' => 'cms', 'with_front' => false ),
    'query_var'       => false,
    'menu_position'   => 100,
    'taxonomies'      => array('post_tag'),
    'supports'        => array('title', 'editor', 'tags', 'thumbnail', 'author')
  ));

  register_post_type('video', array(
    'labels' => array(
      'name'               => _x('Video Gallery', 'post type general name'),
      'singular_name'      => _x('Video', 'post type singular name'),
      'add_new'            => _x('Add New', 'Search Result'),
      'add_new_item'       => __('Add New Video'),
      'edit_item'          => __('Edit Video'),
      'new_item'           => __('New Video'),
      'view_item'          => __('View Video'),
      'search_items'       => __('Search Video'),
      'not_found'          => __('No Videos found'),
      'not_found_in_trash' => __('No Videos found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'has_archive'     => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => array('slug' => 'videos', 'with_front' => false),
    'query_var'       => true,
    'menu_position'   => 100,
    'taxonomies'      => array('playlist'),
    'supports'        => array('title', 'editor', 'comments', 'page-attributes')
  ));
}

//Configure Video gallery list
add_filter('manage_video_posts_columns', 'video_columns_head');
add_action('manage_video_posts_custom_column', 'video_columns_content', 10, 2);
function video_columns_head($defaults) {
  $defaults['playlist'] = 'Playlist';
  $defaults['video_tag'] = 'Tags';
  $defaults['prev'] = 'Thumbnail';

  if (isset($defaults['thumbnail']))
  {
    unset ($defaults['thumbnail']);
  }
  return $defaults;
}
function video_columns_content($column_name, $post_ID) {
  if ($column_name == 'prev')
  {
    $video_url = get_post_meta($post_ID, '_cq_video_url', true);
    $prev = video_image($video_url);
    if ($prev)
    {
      echo '<a href="' . $video_url . '" target="_blank"><img class="cq_video_tmb" src="' . $prev . '" /></a>';
    }
  }
  if ($column_name == 'playlist' || $column_name == 'video_tag')
  {
    $pls = get_the_terms($post_ID, $column_name);
    if (is_array($pls))
    {
      foreach (array_values($pls) as $k => $pl)
      {
        echo sprintf('<a href="%s">%s</a>',
          admin_url( 'edit-tags.php?action=edit&taxonomy=' . $column_name . '&tag_ID=' . $pl->term_id
          . '&post_type=video'),
          $pl->name . ($k+1 < count($pls) ? ', ' : ' '));
      }
    }
  }
}

//Grab video details to fill in edit form
add_action('wp_ajax_video_details', 'video_details_callback');
function video_details_callback() {
  $data = array();

  $url =  $_POST['url'];

  $data['thumb'] = video_image($url);

  if (strpos($url, 'youtube.com') || strpos($url, 'youtu.be'))
  {
    if (strpos($url, 'youtube.com'))
    {
      $url = parse_url($url);
      $vid = parse_str($url['query'], $output);
      $video_id = $output['v'];
    }
    else
    {
      $video_id=explode('youtu.be/', $url);
      $video_id=$video_id[1];
    }
    $data['video_type'] = 'youtube';
    $data['video_id'] = $video_id;
    $json = json_decode(
      file_get_contents('http://gdata.youtube.com/feeds/api/videos/' . $video_id . '?v=2&alt=json'), true);

    if (isset($json['entry']))
    {
      $json = $json['entry'];

      $data['thumb_1'] = $json['media$group']['media$thumbnail'][0]['url']; // Thumbnail 1
      $data['thumb_2'] = $json['media$group']['media$thumbnail'][1]['url']; // Thumbnail 2
      $data['thumb_3'] = $json['media$group']['media$thumbnail'][2]['url']; // Thumbnail 3
      $data['thumb_large'] = $json['media$thumbnail'][3]['url']; // Large thumbnail

      $data['title'] = nl2br($json['title']['$t']);
      $data['info'] = nl2br($json['media$group']['media$description']['$t']);
    }

  } // End Youtube

  // Handle Vimeo
  else if (strpos($url, 'vimeo.com'))
  {
    $video_id=explode('vimeo.com/', $url);
    $video_id=$video_id[1];
    $data['video_type'] = 'vimeo';
    $data['video_id'] = $video_id;
    $xml = simplexml_load_file('http://vimeo.com/api/v2/video/' . $video_id . '.xml');

    foreach ($xml->video as $video)
    {
      $data['title'] = (string) $video->title;
      $data['info'] = (string) $video->description;
      $data['url'] = (string) $video->url;
      $data['thumb_small'] = (string) $video->thumbnail_small;
      $data['thumb_medium'] = (string) $video->thumbnail_medium;
      $data['thumb_large'] = (string) $video->thumbnail_large;
     } // End foreach
  } // End Vimeo

  echo json_encode($data);

  die();
}

/**
 * Get video thumbnail from youtube or vimeo url
 *
 * @param $url
 * @return string
 */
function video_image($url){
  $image_url = parse_url($url);
  if ($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com')
  {
    $array = explode('&', $image_url['query']);
    return 'http://img.youtube.com/vi/' . substr($array[0], 2) . '/0.jpg';
  }
  else if ($image_url['host'] == 'www.youtu.be' || $image_url['host'] == 'youtu.be')
  {
    $array = explode('/', $image_url['path']);
    return 'http://img.youtube.com/vi/' . $array[1] . '/0.jpg';
  }
  else if ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')
  {
    $hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . substr($image_url['path'], 1).'.php'));
    return $hash[0]['thumbnail_large'];
  }

  return '/images/frontend/multimedia/wpPost/308x301.png';
}

add_filter('map_meta_cap', 'map_meta_cap_editorial', 10, 4);
function map_meta_cap_editorial($caps, $cap, $user_id, $args)
{
  $post = $post_type = null;

  /* If editing, deleting, or reading a editorial, get the post and post type object. */
  if ('edit_editorial' == $cap || 'delete_editorial' == $cap || 'read_editorial' == $cap) {
    $post = get_post($args[0]);
    $post_type = get_post_type_object($post->post_type);

    /* Set an empty array for the caps. */
    $caps = array();
  }

  /* If editing a editorial, assign the required capability. */
  if ('edit_editorial' == $cap) {
    if ($user_id == $post->post_author)
      $caps[] = $post_type->cap->edit_posts;
    else
      $caps[] = $post_type->cap->edit_others_posts;
  }

  /* If deleting a editorial, assign the required capability. */
  elseif ('delete_editorial' == $cap) {
    if ($user_id == $post->post_author)
      $caps[] = $post_type->cap->delete_posts;
    else
      $caps[] = $post_type->cap->delete_others_posts;
  }

  /* If reading a private editorial, assign the required capability. */
  elseif ('read_editorial' == $cap) {
    if ('private' != $post->post_status)
      $caps[] = 'read';
    elseif ($user_id == $post->post_author)
      $caps[] = 'read';
    else
      $caps[] = $post_type->cap->read_private_posts;
  }

  /* Return the capabilities required by the user. */
  return $caps;
}

// hide permalinks on custom post types
add_action('admin_head', 'hide_edit_permalinks_admin_css');
function hide_edit_permalinks_admin_css() {

  global $typenow;
  if ($typenow != 'post' && $typenow != 'page') :

    ?>
  <?php

  endif;

}

// ajax post loading
function cq_ajax_posts_comments() {

  global $wp_query;
  // Add code to index pages.
  if (!is_singular())
  {
    // Queue JS and CSS
    wp_enqueue_script(
      'cq-load-posts', '/wp-content/themes/collectorsquest/js/load-posts.js',
      array('jquery'), '1.0', true
    );

    // What page are we on? And what is the pages limit?
    $max = $wp_query->max_num_pages;
    $paged = (get_query_var('paged') > 1) ? get_query_var('paged') : 1;

    // Add some parameters for the JS.
    wp_localize_script(
      'cq-load-posts', 'cq_i18n',
      array(
        'startPage' => $paged,
        'maxPages'  => $max,
        'nextLink'  => next_posts($max, false)
      )
    );
  }
}
add_action('template_redirect', 'cq_ajax_posts_comments');

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "/images/default.jpg";
  }
  return $first_img;
}

// add_filter('pre_get_posts', 'filter_homepage_posts');
function filter_homepage_posts($query) {

  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

  if (!is_admin() && $paged==1) {
    $limit_number_of_posts = 7;
  } elseif (!is_admin()) {
    $limit_number_of_posts = 8;
  }

  //$query->set('offset', $offset);
  $query->set('posts_per_page', $limit_number_of_posts);

  return $query;
}

add_filter('user_contactmethods', 'my_user_contactmethods');
function my_user_contactmethods($user_contactmethods){

  //unset($user_contactmethods['yim']);
  //unset($user_contactmethods['aim']);
  //unset($user_contactmethods['jabber']);

  $user_contactmethods['twitter'] = 'Twitter Username';
  $user_contactmethods['facebook'] = 'Facebook Username';

  return $user_contactmethods;
}

// multiple excerpt lengths
function cq_excerptlength_firstpost($length) {
  return 64;
}
function cq_excerptlength_archive($length) {
  return 32;
}
function cq_excerpt($length_callback='', $more_callback='') {
  global $post;
  if(function_exists($length_callback)){
    add_filter('excerpt_length', $length_callback);
  }
  if(function_exists($more_callback)){
    add_filter('excerpt_more', $more_callback);
  }
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  $output = '<p>'.$output.'</p>';
  echo $output;
}

// puts link in excerpts more tag
function new_excerpt_more($more) {
  global $post;
  return '...&nbsp;<a class="moretag" href="'. get_permalink($post->ID) . '">more</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

// adds link class for global styles
function add_class_the_tags($html) {
  if (is_single()) {
    $postid = get_the_ID();
    $html = str_replace('<a','<a class="tags"',$html);
    return $html;
  } else {
    return $html;
  }
}
add_filter('the_tags','add_class_the_tags',10,1);

// fixed sidebar on static pages
function add_fixed_sidebar() {

  if (is_page()) : ?>

  <script type="text/javascript" src="/blog/wp-content/themes/collectorsquest/js/jquery-scrolltofixed-min.js"></script>

  <script>

    $(document).ready(function() {
      $('#sidebar').scrollToFixed({
        limit: $('#footer').offset().top - $('#sidebar').innerHeight() - 10
      });
    });


  </script>

  <?php

  endif;

}
add_action('wp_footer','add_fixed_sidebar');

// includes for widgets/metaboxes
require_once __DIR__ .'/lib/widgets/widgets.php';
include_once __DIR__ .'/lib/metaboxes/setup.php';

// comment template
function cq_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment; ?>
  <div <?php comment_class(); ?> id="div-comment-<?php comment_ID() ?>">
    <div id="div-comment-<?php comment_ID() ?>" class="row-fluid user-comment">
      <div class="span2 text-right">
        <?php
          $comment_author_id = get_comment_meta(get_comment_ID(), 'comment_author_id', true);
          $comment_author_slug = get_comment_meta(get_comment_ID(), 'comment_author_slug', true) ?: 'n-a';

          $comment_author_link =
            '<a href="/collector/' . $comment_author_id . '/' . $comment_author_slug . '"
                title="' . $comment->comment_author . '">';

          if ($comment_author_id)
          {
            echo  $comment_author_link . '<img src="/collector/' . $comment_author_id . '/65x65/avatar.jpg"
                  alt="' . $comment->comment_author . '" width="65" height="65" class="gravatar_photo"></a>';
          }
          else
          {
            echo get_avatar( $comment->comment_author_email, 65 );
          }
        ?>
      </div>
      <div class="span10">
        <p class="bubble left">
          <span class="comment-author">
            <?php
              if ($comment_author_id)
              {
                echo $comment_author_link . $comment->comment_author . '</a>';
              }
              else
              {
                comment_author_link();
              }
            ?>
          </span>
          <?php if ($comment->comment_approved == '0') : ?>
          <em>Your comment is awaiting moderation.</em>
          <?php endif; ?>
          <?php echo $comment->comment_content; ?>
          <span class="comment-time"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('edit','',''); ?></span>
        </p>
      </div>
    </div>
  <?php
  }

// Attach comment_author_id variable to post comments
add_action ('comment_post', 'add_meta_settings', 1);
function add_meta_settings($comment_id) {
  add_comment_meta($comment_id, 'comment_author_id', $_POST['comment_author_id'], true);
  add_comment_meta($comment_id, 'comment_author_slug', $_POST['comment_author_slug'], true);
}

// ajax comments
function add_ajaxurl_cdata_to_front() {
  ?>
  <script type="text/javascript">
    //<![CDATA[
    ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';
    //]]>
  </script>


  <script type="text/javascript">
    $('#load_comments').click(function(){

      var post_id = $(this).parent("div").attr("id");
      $(this).text('Loading comments...');

      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "load_comments", post_id: post_id},
        success: function(data){
          jQuery(".commentlist").html(data);

          $('#load_comments').remove();

        }
      });
      return false;
    });
  </script>
<?php
}
add_action( 'wp_footer', 'add_ajaxurl_cdata_to_front', 11);

add_action( 'wp_ajax_load_comments', 'load_comments' );
add_action( 'wp_ajax_nopriv_load_comments', 'load_comments' );
function load_comments() {

  global $post, $wp_query, $post_id;
  $post_id = isset($_POST['post_id'])? intval($_POST['post_id']) : 0;
  $args = array(
    'post_id' => $post_id,
    'status' => 'approve',
    'order'   => 'ASC'
  );
  $wp_query->comments = get_comments( $args );
  wp_list_comments('type=comment&callback=cq_comment&style=div&per_page=-1');
  comments_template();
  die();
}

// ajax how-tos
function load_how_to()
{
  $id = $_GET['group_id'];

  //global variables
  global $wpfaqDb, $wpfaqGroup;

  $wpfaqDb->model = $wpfaqGroup->model;
  $groups = $wpfaqDb->find_all();

  foreach ($groups as $group)
  {
    if ($group->id == $id)
    {
      echo '<h1>'. $group -> name .'</h1>';
      break;
    }
  }

  echo do_shortcode('[wpfaqgroup id=' .$id  . ']');

  echo sprintf(<<<START
  <div class="form-actions">
    <a href="#" class="btn btn-primary spacer-right-15" onClick="window.location.href = '%s';">
      Ok, I got it!
    </a>
    <button type="reset" class="btn" onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Close
    </button>
  </div>
START
  , $_GET['redirect']);

  die();
}
add_action( 'wp_ajax_load_how_to', 'load_how_to' );
add_action( 'wp_ajax_nopriv_load_how_to', 'load_how_to' );

// add TinyMCE editor to the "Biographical Info" field in a user profile
function kpl_user_bio_visual_editor( $user ) {
  // Requires WP 3.3+ and author level capabilities
  if ( function_exists('wp_editor') && current_user_can('publish_posts') ):
    ?>
    <script type="text/javascript">
      (function($){
        // Remove the textarea before displaying visual editor
        $('#description').parents('tr').remove();
      })(jQuery);
    </script>

  <h3><?php _e('Biography', 'collectorsquest'); ?></h3>
    <table class="form-table">
      <tr>
        <th><label for="description"><?php _e('Biographical Info'); ?></label></th>
        <td>
          <?php
          $description = get_user_meta( $user->ID, 'description', true);
          wp_editor( $description, 'description' );
          ?>
          <p class="description"><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.'); ?></p>
        </td>
      </tr>
    </table>
  <?php
  endif;
}
add_action('show_user_profile', 'kpl_user_bio_visual_editor');
add_action('edit_user_profile', 'kpl_user_bio_visual_editor');

// Remove textarea filters from description field
function kpl_user_bio_visual_editor_unfiltered() {
  remove_all_filters('pre_user_description');
}
add_action('admin_init','kpl_user_bio_visual_editor_unfiltered');

// add tag field to user profile
function cq_add_custom_user_profile_fields( $user ) {
  ?>
  <h3><?php _e('Collectors Quest Tags', 'collectorsquest'); ?></h3>
  <table class="form-table">
    <tr>
      <th>
        <label for="address"><?php _e('User Tags', 'collectorsquest'); ?>
        </label></th>
      <td>
        <input type="text" name="user_tags" id="user_tags" value="<?php echo esc_attr( get_the_author_meta( 'user_tags', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e('Please enter your tags. (1,2,3)', 'collectorsquest'); ?></span>
      </td>
    </tr>
  </table>
<?php }
function cq_save_custom_user_profile_fields( $user_id ) {
  if ( !current_user_can( 'edit_user', $user_id ) )
    return FALSE;
  update_user_meta( $user_id, 'user_tags', $_POST['user_tags'] );
}
add_action( 'show_user_profile', 'cq_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'cq_add_custom_user_profile_fields' );
add_action( 'personal_options_update', 'cq_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'cq_save_custom_user_profile_fields' );

// correcting comment count
add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
  if ( ! is_admin() ) {
    global $id;
    $comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
    return count($comments_by_type['comment']);
  } else {
    return $count;
  }
}

// filter text strings - (FAQ plugin/)
function cq_filter_gettext( $translated, $original, $domain ) {

  // This is an array of original strings
  // and what they should be replaced with
  $strings = array(
    'Use the form below to search the FAQs' => '',
    'Use the form below to ask a question' => '',
    'Your question has been submitted for answering' => 'Your question has been submitted. We\'ll respond as soon as possible!',
    'Search for' => '',
    'Ask Question' => 'Submit',
    'Howdy, %1$s' => 'We love you, %1$s!',
    // Add some more strings here
  );

  // See if the current string is in the $strings array
  // If so, replace it's translation
  if ( isset( $strings[$original] ) ) {
    // This accomplishes the same thing as __()
    // but without running it through the filter again
    $translations = &get_translations_for_domain( $domain );
    $translated = $translations->translate( $strings[$original] );
  }

  return $translated;
}
add_filter( 'gettext', 'cq_filter_gettext', 10, 3 );

// helper functions
function get_top_ancestor($id) {
  $current = get_post($id);
  if(!$current->post_parent){
    return $current->ID;
  } else {
    return get_top_ancestor($current->post_parent);
  }
}
function is_child($pageID) {
  global $post;
  if( is_page() && ($post->post_parent==$pageID) ) {
    return true;
  } else {
    return false;
  }
}

// gets post thumbnails
function get_post_image_url($size = 'full', $count = 0) {
  global $post, $posts;

  // check for post thumbnail
  $image_id = get_post_thumbnail_id($post->ID);
  $image_url = wp_get_attachment_image_src($image_id,'full');
  $image_url = $image_url[0];


  // if no post thumbnail check for attachments
  if (!$image_url) :
    $args = array(
      'post_parent' => $post->ID,
      'post_type' => 'attachment',
      'post_mime_type' => 'image',
      'orderby' => 'menu_order',
      'order' => 'ASC',
      'offset' => '0',
      'numberposts' => -1
    );

    $images = get_posts($args);

    // return first attachment over 300px wide if found
    if ( count( $images ) > 0 ) :

      $c = 0;
      foreach ($images as $image) {
        $image_attributes = wp_get_attachment_image_src( $image->ID, 'full' );
        if ($count == 1 && $image_attributes[1] >= 300) {
          $img = wp_get_attachment_image_src($images[$c]->ID, $size);
          end;
        } else {
          $img = wp_get_attachment_image_src($images[0]->ID, $size);
        }
      $c++;
      }

      //return wp_get_attachment_url($images[0]->ID);
      $img = $img[0];

    else :
      // else return first image from post content
      $img = '/blog/wp-content/themes/collectorsquest/thumb.php?src='.catch_that_image().'&w='.$img[1].'&h='.$img[2].'&zc=1&a=t';
    endif;

  // if there is a post thumbnail
  else :
    $image_url = wp_get_attachment_image_src($image_id,$size);
    $image_url = $image_url[0];
    $img = $image_url;
  endif;

  return $img;

}



// include thumbnails in rss feed
function insertThumbnailRSS($content) {
  global $post;
  $content = '<div><img src="/blog/wp-content/themes/collectorsquest/thumb.php?src=' . get_post_image_url() . '&w=140&h=140&zc=1&a=t" alt="Post Thumbnail Image" style="display:block;float:left;margin-right:20px;margin-bottom:20px;" /></div>' . $content;
  return $content;
}
add_filter('the_excerpt_rss', 'insertThumbnailRSS');
add_filter('the_content_feed', 'insertThumbnailRSS');

// sharper thumbnails
// http://wordpress.org/extend/plugins/sharpen-resized-images/developers/
function ajx_sharpen_resized_files( $resized_file ) {

  $image = wp_load_image( $resized_file );
  if ( !is_resource( $image ) )
    return new WP_Error( 'error_loading_image', $image, $file );

  $size = @getimagesize( $resized_file );
  if ( !$size )
    return new WP_Error('invalid_image', __('Could not read image size'), $file);
  list($orig_w, $orig_h, $orig_type) = $size;

  switch ( $orig_type ) {
    case IMAGETYPE_JPEG:
      $matrix = array(
        array(-1, -1, -1),
        array(-1, 16, -1),
        array(-1, -1, -1),
      );

      $divisor = array_sum(array_map('array_sum', $matrix));
      $offset = 0;
      imageconvolution($image, $matrix, $divisor, $offset);
      imagejpeg($image, $resized_file,apply_filters( 'jpeg_quality', 90, 'edit_image' ));
      break;
    case IMAGETYPE_PNG:
      return $resized_file;
    case IMAGETYPE_GIF:
      return $resized_file;
  }

  return $resized_file;
}
add_filter('image_make_intermediate_size', 'ajx_sharpen_resized_files',900);

// add custom sizes to media uploader - http://stackoverflow.com/questions/5032906/how-can-i-add-custom-image-sizes-to-wordpress-but-have-them-in-the-admin
function my_attachment_fields_to_edit_filter($form_fields, $post) {
  if (!array_key_exists('image-size', $form_fields)) return $form_fields;

  global $_wp_additional_image_sizes;
  foreach($_wp_additional_image_sizes as $size => $properties) {
    if ($size == 'post-thumbnail') continue;

    $label = ucwords(str_replace('-', ' ', $size));
    $cssID = "image-size-{$size}-{$post->ID}";

    $downsize = image_downsize($post->ID, $size);
    $enabled = $downsize[3];

    $html = '<input type="radio" ' . disabled($enabled, false, false) . 'name="attachments[' . $post->ID. '][image-size]" id="' . $cssID . '" value="' . $size .'">';
    $html .= '<label for="'. $cssID . '">' . $label . '</label>';
    if ($enabled) $html .= ' <label for="' . $cssID . '" class="help">(' . $downsize[1] . '&nbsp;×&nbsp;' . $downsize[2] . ')</label>';
    $form_fields['image-size']['html'] .= '<div class="image-size-item">' . $html . '</div>';
  }

  return $form_fields;
}
add_filter('attachment_fields_to_edit', 'my_attachment_fields_to_edit_filter', 100, 2);




// fix wp image margins from - http://rathercurious.net
class fixImageMargins{
  public $xs = 0; //change this to change the amount of extra spacing

  public function __construct(){
    add_filter('img_caption_shortcode', array(&$this, 'fixme'), 10, 3);
  }
  public function fixme($x=null, $attr, $content){

    extract(shortcode_atts(array(
      'id'    => '',
      'align'    => 'alignnone',
      'width'    => '',
      'caption' => ''
    ), $attr));

    if ( 1 > (int) $width || empty($caption) ) {
      return $content;
    }

    if ( $id ) $id = 'id="' . $id . '" ';

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width + $this->xs) . 'px">'
      . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
  }
}
$fixImageMargins = new fixImageMargins();


// hiding JetPack Menu from non-admins
function remove_menu_items() {
  if (!current_user_can( 'administrator' )) {
    global $menu;
    $restricted = array(__('jetpack'),__('Jetpack'));
    end ($menu);
    while (prev($menu)){
      $value = explode(' ',$menu[key($menu)][0]);
      if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
        unset($menu[key($menu)]);}
    }
  }
}
add_action('admin_menu', 'remove_menu_items',11);




// REQUIRE Post Tags
function tag_remind_init() {
  wp_enqueue_script('jquery');
}
function tag_remind() {
  global $typenow;
  if ($typenow == 'post') {
    echo "<script type='text/javascript'>\n";
    echo "//<![CDATA[\n";
    echo "
    jQuery('#publish').click(function() {
      tagSelected=false;
      if (jQuery('.tagchecklist span').length >0) {
        tagSelected=true;
      }
      if (tagSelected==false) {
        alert('You must add at least one tag.');
        setTimeout(\"jQuery('#ajax-loading').css('visibility', 'hidden');\",
        100);
        jQuery('[id^=\"tagsdiv-post_tag\"]').css('background', '#FFB');
        setTimeout(\"jQuery('#publish').removeClass('button-primary-disabled');\", 100)
        return false;
      }
    });
    ";
    echo "// ]]>\n";
    echo "</script>\n";
  }
}
//add_action('admin_init', 'tag_remind_init');
add_action('edit_form_advanced', 'tag_remind');




// Add a "Download Original" link to the media row actions
function add_media_row_action( $actions, $post ) {
  if ( 'image/' != substr( $post->post_mime_type, 0, 6 ) || ! current_user_can('edit_post', $post->ID ) )
    return $actions;
$img = wp_get_attachment_url( $post->ID );
  $url = wp_nonce_url( admin_url( 'tools.php?page=download-original&goback=1&ids=' . $post->ID ), 'download-original' );
  $actions['download_original'] = '<a href="' . esc_url( $img ) . '" title="' . esc_attr( __( "Right Click, and Save Link As to download the original image", 'download-original' ) ) . '">' . __( 'Download Original', 'download-original' ) . '</a>';

  return $actions;
}
add_filter('media_row_actions', 'add_media_row_action', 10, 3);

/**
 * The formatted output of a list of pages.
 *
 * @param string|array $args Optional. Overwrite the defaults.
 * @return string Formatted output in HTML.
 */
function custom_wp_link_pages( $args = '' ) {
  global $page, $numpages, $multipage, $more, $pagenow;

  $defaults = array(
    'before' => '<p id="post-pagination" class="entry-meta span12">',
    'after' => '</p>',
    'text_before' => '',
    'text_after' => '',
    'next_or_number' => 'next',
    'nextpagelink' => __( 'Click Here to See #' ),
    'previouspagelink' => __( 'Click Here to See #' ),
    'pagelink' => '%',
    'echo' => 1
  );

  $r = wp_parse_args( $args, $defaults );
  $r = apply_filters( 'wp_link_pages_args', $r );
  extract( $r, EXTR_SKIP );

  $output = '';
  if ( $multipage ) {
    if ( 'number' == $next_or_number ) {
      $output .= $before;
      for ( $i = 1; $i < ( $numpages + 1 ); $i = $i + 1 ) {
        $j = str_replace( '%', $i, $pagelink );
        $output .= ' ';
        if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) )
          $output .= _wp_link_page( $i );
        else
          $output .= '<span class="current-post-page">';

        $output .= $text_before . $j . $text_after;
        if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) )
          $output .= '</a>';
        else
          $output .= '</span>';
      }
      $output .= $after;
    } else {
      if ( $more ) {
        $output .= $before;
        $i = $page - 1;
        if ( $i && $more ) {
          $output .= _wp_link_page( $i );
          $page_number  = (string) $numpages - $page + 2;
          $output .= '<span class="previous"><i class="icon-chevron-left"></i>&nbsp;&nbsp;' .
            $text_before . $previouspagelink . $page_number . $text_after . '<span></a>';
        }
        $i = $page + 1;
        if ( $i <= $numpages && $more ) {
          $output .= _wp_link_page( $i );
          $page_number  = (string) $numpages - $page;
          $output .= '<span class="next">' . $text_before . $nextpagelink . $page_number . $text_after .
            '&nbsp;&nbsp;<i class="icon-chevron-right"></i><span></a>';
        }
        $output .= $after;
      }
    }
  }

  if ( $echo )
    echo $output;

  return $output;
}


// Add nextpage button to TinyMCE
add_filter('mce_buttons','cq_wysiwyg_editor');
function cq_wysiwyg_editor($mce_buttons)
{
  $pos = array_search('wp_more', $mce_buttons, true);

  if ($pos !== false)
  {
    $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
    $tmp_buttons[] = 'wp_page';
    $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos + 1));
  }

  return $mce_buttons;
}

// validate collectible IDs with sizes
function validate_collectible_ids_with_sizes($meta, $post_id)
{
  $valid_homepage_collectible_ids = validate_collectible_ids_helper ($meta['cq_homepage_collectible_ids']);
  $valid_collectible_ids = validate_collectible_ids_helper ($meta['cq_collectible_ids']);

  if (!$valid_homepage_collectible_ids)
  {
    add_admin_message('There was an error in the format of Homepage Collectible IDs filed! Please correct it and save again', true);
  }

  if (!$valid_collectible_ids)
  {
    add_admin_message('There was an error in the format of Collectible IDs filed! Please correct it and save again', true);
  }

  // if there is error we abort the save
  if (!$valid_collectible_ids || !$valid_homepage_collectible_ids)
  {
    return false;
  }

  // if no error - save post as usual
  return $meta;
}

// do the actual id and size validation
function validate_collectible_ids_helper($meta)
{
  $cq_collectible_ids = explode(',', (string) $meta);
  $cq_collectible_ids = array_map('trim', $cq_collectible_ids);
  $cq_collectible_ids = array_filter($cq_collectible_ids);

  foreach ($cq_collectible_ids as $collectible_id)
  {
    if (strstr($collectible_id, ':'))
    {
      $parsed_value = explode(':', $collectible_id);
      // check if first part is integer
      if ($parsed_value[0] != (int) $parsed_value[0])
      {
        return false;
      }
      // check if second part is some of the following
      switch ($parsed_value[1]) {
        case '2x2': break;
        case '2x1': break;
        case '1x2': break;
        case '1x1': break;
        case '1x3': break;
        case '2x3': break;
        case '3x3': break;
        case '3x2': break;
        case '3x1': break;
        default:
          return false;
      }
    }

  }

  return true;
}

/**
 * Messages with the default wordpress classes
 */
function showMessage($message, $errormsg = false)
{
  if ($errormsg) {
    echo '<div id="message" class="error">';
  }
  else {
    echo '<div id="message" class="updated fade">';
  }

  echo "<p>$message</p></div>";
}

/**
 * Display custom messages
 */
function show_admin_messages()
{
  if(isset($_COOKIE['wp-admin-messages-normal'])) {
    $messages = strtok($_COOKIE['wp-admin-messages-normal'], "@@");

    while ($messages !== false) {
      showMessage($messages, true);
      $messages = strtok("@@");
    }

    setcookie('wp-admin-messages-normal', null);
  }

  if(isset($_COOKIE['wp-admin-messages-error'])) {
    $messages = strtok($_COOKIE['wp-admin-messages-error'], "@@");

    while ($messages !== false) {
      showMessage($messages, true);
      $messages = strtok("@@");
    }

    setcookie('wp-admin-messages-error', null);
  }
}

/**
 * Hook into admin notices
 */
add_action('admin_notices', 'show_admin_messages');

/**
 * User Wrapper
 */
function add_admin_message($message, $error = false)
{
  if(empty($message)) return false;

  if($error) {
    setcookie('wp-admin-messages-error', $_COOKIE['wp-admin-messages-error'] . '@@' . $message, time()+5);
  } else {
    setcookie('wp-admin-messages-normal', $_COOKIE['wp-admin-messages-normal'] . '@@' . $message, time()+5);
  }
}

/**
 * Return array of widgets for sidebar, see dynamic_sidebar()
 *
 * @param $index
 * @return array
 */
function get_sidebar_widgets($index)
{
  global $wp_registered_sidebars, $wp_registered_widgets;
  $result = array();

  if ( is_int($index) )
  {
    $index = "sidebar-$index";
  }
  else
  {
    $index = sanitize_title($index);
    foreach ( (array) $wp_registered_sidebars as $key => $value )
    {
      if ( sanitize_title($value['name']) == $index )
      {
        $index = $key;
        break;
      }
    }
  }

  $sidebars_widgets = wp_get_sidebars_widgets();
  if ( empty( $sidebars_widgets ) )
    return false;

  if ( empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $sidebars_widgets)
    || !is_array($sidebars_widgets[$index]) || empty($sidebars_widgets[$index]) )
    return false;

  $sidebar = $wp_registered_sidebars[$index];

  $did_one = false;
  foreach ( (array) $sidebars_widgets[$index] as $id )
  {

    if ( !isset($wp_registered_widgets[$id]) ) continue;

    $params = array_merge(
      array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
      (array) $wp_registered_widgets[$id]['params']
    );

    // Substitute HTML id and class attributes into before_widget
    $classname_ = '';
    foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn )
    {
      if ( is_string($cn) )
        $classname_ .= '_' . $cn;
      elseif ( is_object($cn) )
        $classname_ .= '_' . get_class($cn);
    }
    $classname_ = ltrim($classname_, '_');
    $params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);

    $params = apply_filters( 'dynamic_sidebar_params', $params );

    $callback = $wp_registered_widgets[$id]['callback'];

    do_action( 'dynamic_sidebar', $wp_registered_widgets[$id] );

    if ( is_callable($callback) )
    {
      ob_start();
      call_user_func_array($callback, $params);

      $result[] = ob_get_clean();
    }
  }

  return $result;
}

//Fix for oembed
add_filter('http_request_args', 'bal_http_request_args', 100, 1);
function bal_http_request_args($r)
{
  $r['timeout'] = 15;

  return $r;
}

add_action('http_api_curl', 'bal_http_api_curl', 100, 1);
function bal_http_api_curl($handle) //called on line 1315
{
  curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 15 );
  curl_setopt( $handle, CURLOPT_TIMEOUT, 15 );
}