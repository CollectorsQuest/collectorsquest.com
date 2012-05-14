<?php

/** http://codex.wordpress.org/Post_Thumbnails */
add_theme_support('post-thumbnails');

/** @see http://blurback.com/post/1479456356/permissions-with-wordpress-custom-post-types */

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
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'title', 'editor')
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
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'editor', 'thumbnail')
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
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'custom-fields')
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
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'editor', 'custom-fields', 'thumbnail')
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
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'custom-fields')
  ));

  register_post_type('marketplace_featured', array(
    'labels' => array(
      'name'               => _x('Marketplace Featured', 'post type general name'),
      'singular_name'      => _x('Featured Collectible', 'post type singular name'),
      'add_new'            => _x('Add New', 'Featured Collectible'),
      'add_new_item'       => __('Add New Featured Collectible'),
      'edit_item'          => __('Edit Featured Collectible'),
      'new_item'           => __('New Featured Collectible'),
      'view_item'          => __('View Featured Collectible'),
      'search_items'       => __('Search Featured Collectible'),
      'not_found'          => __('No Featured Collectibles found'),
      'not_found_in_trash' => __('No Featured Collectibles found in Trash'),
      'parent_item_colon'  => ''
    ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'editorial',
    'capabilities'    => $capabilities,
    'hierarchical'    => false,
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title','custom-fields')
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
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title')
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
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title', 'editor', 'custom-fields', 'thumbnail')
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
    'rewrite'         => false,
    'query_var'       => false,
    'menu_position'   => 100,
    'supports'        => array('title')
  ));
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
  <style type="text/css">
    <!--
    #titlediv
    {
      margin-bottom: 10px;
    }
    #edit-slug-box
    {
      display: none;
    }
    -->
  </style>
  <?php

  endif;

}





if ($_SERVER['HTTP_HOST'] == 'www.collectorsquest.dev' || $_SERVER['HTTP_HOST'] == 'www.collectorsquest.next' || $_SERVER['HTTP_HOST'] == 'www.cqnext.com') {

  // ajax post loading
  function cq_ajax_posts() {

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
        'cq-load-posts', 'cq',
        array(
          'startPage' => $paged,
          'maxPages'  => $max,
          'nextLink'  => next_posts($max, false)
        )
      );
    }
  }
  add_action('template_redirect', 'cq_ajax_posts');

  function catch_that_image()
  {
    global $post;
    $first_img = '';
    ob_start();
    ob_end_clean();
    if (preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches))
    {
      $first_img = $matches[1][0];
    }

    // Defines a default image
    if(empty($first_img))
    {
      $first_img = "/images/default.jpg";
    }

    return $first_img;
  }

  // add_filter('pre_get_posts', 'filter_homepage_posts');
  /**
   * @param $query WP_Query
   * @return mixed
   */
  function filter_homepage_posts($query)
  {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    if (!is_admin() && $paged==1) {
      $limit_number_of_posts = 7;
    } elseif (!is_admin()) {
      $limit_number_of_posts = 8;
    } else {
      $limit_number_of_posts = 8;
    }

    // $query->set('offset', $offset);
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
  function cq_excerptlength_firstpost() {
    return 64;
  }
  function cq_excerptlength_archive() {
    return 32;
  }
  function cq_excerpt($length_callback='', $more_callback='') {
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
  function new_excerpt_more() {
    global $post;
    return '...&nbsp;<a class="moretag" href="'. get_permalink($post->ID) . '">more</a>';
  }
  add_filter('excerpt_more', 'new_excerpt_more');

  // adds link class for global styles
  function add_class_the_tags($html){
    if (is_single()) {
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
        marginTop: 10,
        limit: $('#footer').offset().top - 600
      });
    });


  </script>

  <?php

      endif;

    }
  add_action('wp_footer','add_fixed_sidebar');

  // includes for widgets/metaboxes
  require_once __DIR__.'/lib/widgets/widgets.php';
  include_once __DIR__.'lib/metaboxes/setup.php';

}

