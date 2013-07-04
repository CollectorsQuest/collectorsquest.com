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
  <div id="post-<?= $post->ID ?>" class="post row-fluid">
    <div class="entry-content span12">
      <?php the_content(); ?>
      <div>
        <?php echo convert_videos(get_post_meta( $post->ID, '_video_url', true )); ?>
      </div>
    </div>
    <br clear="all"/>
    <div class="entry-meta span12" style="width: 608px;">
      <span class="meta-text">
          <span class="author-info">
            <span class="entry-date">Posted
              <?= get_the_date('M jS, Y'); ?>
            </span>
            <?php edit_post_link('Edit', ' | ', ''); ?>
          </span>
        </span>
      <?php if (!$is_mobile) : ?>
        <div id="social-sharing" class="blue-actions-panel entry-share pull-right share">
          <!-- AddThis Button BEGIN -->
          <a class="btn btn-lightblue btn-mini-social addthis_button_email">
            <i class="mail-icon-mini"></i> Email
          </a>
          <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
          <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
          <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?php echo get_post_image_url(); ?>"
             pi:pinit:layout="horizontal"></a>
          <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="75"></a>
          <!-- AddThis Button END -->
        </div>
      <?php endif; ?>
    </div>
    <br clear="all"/>
    <div id="comments">
      <?php comments_template(); ?>
    </div>

  </div>



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
  foreach ($widgets[$sidebar] as $widget) {
    ob_start();
    $widget = substr($widget, 0, strrpos($widget, '-'));
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
