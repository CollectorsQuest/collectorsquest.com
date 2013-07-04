<?php
/**
 * Template Name: Video Gallery Page
 *
 * Selectable from a dropdown menu on the edit page screen.
 */
?>

<?php
/**
 * @var $wp_query WP_Query
 */

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
      <h1 class="Chivo webfont" style="visibility: visible; "><?php the_title() ?></h1>
    </div>
    <div class="back-nav span5">
      <!--    <a href="/blog/">Back to Latest News &rarr;</a>-->
    </div>
  </div>
  <div id="blog-contents" class="singular">
    <?php
    $current_page = get_query_var('paged') ? get_query_var('paged') : 1;
    $mypost = array(
      'post_type' => 'video',
      'posts_per_page' => 6,
      'paged' => $current_page
    );
    $loop = new WP_Query( $mypost );
    ?>
    <div class="row" style="position: relative;">
      <?php if($loop->have_posts()):
        while ( $loop->have_posts() ) : $loop->the_post();?>
        <div class="span3" id="post-<?php the_ID(); ?>" style="height: 115px">

<!--          <a title="--><?php //the_title(); ?><!--" href="http://video.collectorsquest.com/video/CQ-Promo-on-HISTORY" style="position: absolute">-->
<!--            <img width="140" alt="CQ Promo on HISTORY" src="http://s3.amazonaws.com/magnifythumbs/19RY951XVQ7NKF7Q.jpg">-->
<!--            <span class="sidebar-video-play-button"></span>-->
<!--          </a>-->
          <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>

      <?php /*

            <!-- Display featured image in right-aligned floating div -->
            <div style="float: right; margin: 10px">
              <?php the_post_thumbnail( array( 100, 100 ) ); ?>
            </div>

            <!-- Display Title and Aupaginthor Name -->
            <strong>Title: </strong><?php the_title(); ?><br />
            <strong>Director: </strong>
            <?php echo esc_html( get_post_meta( get_the_ID(), 'movie_director', true ) ); ?>
            <br />
            <a href="<?php the_permalink() ?>">aaaaaa</a>



          <!-- Display movie review contents -->
          <div class="entry-content"><?php the_content(); ?></div> */ ?>
        </div>




      <?php endwhile; ?>
       <?php
    //  pagination($loop, get_bloginfo( $url ));

        $format = get_option('permalink_structure') ? 'page/%#%/' : '&page=%#%';
        echo paginate_links(array(
          'base' => get_pagenum_link(1) . '%_%',
          'format' => $format,
          'current' => $current_page,
          'total' => $loop->max_num_pages,
          'mid_size' => 4,
          'type' => 'list'
        ));
      endif; ?>
    </div>


    <?php wp_reset_query(); ?>
  </div>
<?php $content = ob_get_clean(); ?>

<?php
ob_start();
get_footer();
wp_footer();
$footer = ob_get_clean();
?>

<?php

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
  "http://www." . $domain . "/_blog/index?_session_id=" . $_COOKIE['cq_frontend'] . "&key=" . $key . '&env=' . SF_ENV
);

$layout = str_replace(
  array('<!-- Blog Head //-->', '<!-- Blog Content //-->', '<!-- Blog Footer //-->'),
  array($head, $content, $footer),
  $layout
);

$array = array();
$widgets = get_option('sidebars_widgets');

if (is_array($widgets['video-gallery-sidebar']))
  foreach ($widgets['video-gallery-sidebar'] as $widget) {
    ob_start();
    $widget = substr($widget,0,strrpos($widget,'-'));
    the_widget($widget, $args, $instance);
    $widgout = ob_get_clean();
    $array[] = $widgout;
  }

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