<?php
/**
 * @var $wp_query WP_Query
 */
query_posts($query_string.'&showposts=6');
$data = array();

$data['title'] = wp_title('', false);
$data['the_id'] = get_the_ID();
$data['is_page'] = is_page();
$data['is_single'] = is_single();
$data['is_category'] = is_category();
$data['is_tag'] = is_tag();
$data['is_front_page'] = is_front_page();
$data['is_author'] = is_author();

if (function_exists('bcn_display'))
{
  $data['breadcrumbs'] = bcn_display(true);
}

wp_deregister_script('jquery');
wp_register_script('jquery', '/wp-content/themes/collectorsquest/js/empty.js', array(), '1.7.2', 1);
wp_enqueue_script('jquery');

wp_enqueue_style('cq-styles', '/wp-content/themes/collectorsquest/css/smartWizard.css', array(), '1.0', 'all');


ob_start();
wp_head();
$head = ob_get_clean();

// Optimize the WordPress javascripts (avoid duplicates with out own scripts)
include __DIR__ .'/javascripts.php';

ob_start();
get_header();

// determine if the user is on a mobile device
$is_mobile = (boolean) @$_SERVER['mobile'];

?>

  <div class="row-fluid header-bar">

    <div class="span7">
      <h1 class="Chivo webfont" style="visibility: visible; ">Video Gallery</h1>
    </div>
    <div class="back-nav span5">
      <!--    <a href="/blog/">Back to Latest News &rarr;</a>-->
    </div>
  </div>
  <div id="blog-contents" class="not-singular thumbnails video_gallery_grid">

        <?php
      if(have_posts()) : while(have_posts()) : the_post();
          $video_url = get_post_meta( $post->ID, '_cq_video_url', true );
          ?>

          <div class="span4 post">
            <a href="<?php the_permalink() ?>" class="thumbnail<?= strpos($video_url, 'vimeo.com') ? ' vimeo' : '' ?>">
              <img src="<?= video_image($video_url) ?>" alt="<?php the_title(); ?>">
            </a>
            <h4>
              <a href="<?php the_permalink() ?>">
                <?php the_title(); ?>
              </a>
            </h4>
            <span class="sidebar-video-play-button" onclick="location.href = '<?php the_permalink() ?>';"></span>
          </div>


    <?php endwhile; endif; ?>


  </div>
<?php $content = ob_get_clean(); ?>

<?php
ob_start();
get_footer();
wp_footer();
$footer = ob_get_clean();
?>

<?php

$key = md5(serialize($data));

if (function_exists('xcache_set')) {
  xcache_set($key, $data, 10);
}
else {
  zend_shm_cache_store($key, $data, 10);
}

switch (SF_ENV)
{
  case 'dev':
    $domain = 'collectorsquest.dev';
    break;
  case 'next':
    $domain = 'cqnext.com';
    break;
  case 'stg':
    $domain = 'cqstaging.com';
    break;
  case 'prod':
  default:
    $domain = 'collectorsquest.com';
    break;
}

$layout = file_get_contents(
  "http://www." . $domain . "/_blog/index180?_session_id=" . $_COOKIE['cq_frontend'] . "&key=" . $key
  . '&env=' . SF_ENV
);

$layout = str_replace(
  array('<!-- Blog Head //-->', '<!-- Blog Content //-->', '<!-- Blog Footer //-->'),
  array($head, $content, $footer),
  $layout
);

$array = get_sidebar_widgets('singular-sidebar');


// Make sure the array has at least 9 elements
$array = array_pad($array, 9, '');

echo str_replace(
  array(
    '<!-- Blog Sidebar Widget1 //-->',
    '<!-- Blog Sidebar Widget2 //-->',
    '<!-- Blog Sidebar Widget3 //-->',
    '<!-- Blog Sidebar Widget4 //-->',
    '<!-- Blog Sidebar Widget5 //-->',
    '<!-- Blog Sidebar Widget6 //-->',
    '<!-- Blog Sidebar Widget7 //-->',
    '<!-- Blog Sidebar Widget8 //-->',
    '<!-- Blog Sidebar Widget9 //-->'),
  $array,
  $layout
);

?>