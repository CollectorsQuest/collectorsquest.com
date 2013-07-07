<?php
/**
 * @var $wp_query WP_Query
 */
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
        <?php echo wp_oembed_get(get_post_meta( $post->ID, '_cq_video_url', true ), array('width' => 620)); ?>
      </div>
    </div>

    <br clear="all"/>

    <?php if (has_term('', 'video_tag')): ?>
      <div>
          <div class="section-title">
            <h2>Tags</h2>
          </div>
          <?php echo get_the_term_list( $post->ID, 'video_tag', '', ' ', '' ) ?>
      </div>
    <?php endif; ?>

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
    "http://www." . $domain . "/_blog/index?_session_id=" . $_COOKIE['cq_frontend'] . "&key=" . $key . '&env=' . SF_ENV
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
