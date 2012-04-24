<?php
/**
 * @var $wp_query WP_Query
 */

$data = array();

$data['is_page'] = is_page();
$data['is_single'] = is_single();
$data['is_category'] = is_category();
$data['is_tag'] = is_tag();

if (is_category())
{
  $data['category'] = $wp_query->get_queried_object()->name;
}
else if (is_tag())
{
  $data['tag'] = $wp_query->get_queried_object()->name;
}


ob_start();
wp_head();
$head = ob_get_clean();


ob_start();
get_header();

?>

<?php if (is_single()): ?>
<div class="row-fluid header-bar">
  <div class="span7">
    <h1 class="Chivo webfont" style="visibility: visible; ">News Article</h1>
  </div>
  <div class="back-nav span5">
    <a href="/blog/">Back to News Landing Page &rarr;</a>
  </div>
</div>
<?php elseif (is_front_page()): ?>
<div class="row-fluid header-bar">
  <div class="span11">
    <h1 class="Chivo webfont" style="visibility: visible; ">Latest News</h1>
  </div>
</div>
<?php elseif (is_author()): ?>
<div class="row-fluid header-bar">
  <div class="span11">
    <h1 class="Chivo webfont" style="visibility: visible; "><?php the_author() ?></h1>
  </div>
</div>
<?php endif;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

?>


<div id="blog-contents" class="<?php if(is_singular()): echo 'singular'; else : echo 'not-singular'; endif; ?>">
  <?php if (have_posts()) : ?>

  <?php
  if($paged>1):
    $count = 9;
  else :
    $count = 0;
  endif;

  $lastclass = 0;

  if ($paged==2) :
    query_posts('offset=7&posts_per_page=8');
  elseif ($paged>2) :
    query_posts('offset='. (($paged*8)-9) .'&posts_per_page=8');
  endif;
  ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php
    if (is_single() || is_page())
    {
      $categories = get_the_category();
      foreach ($categories as $k => $category)
      {
        if ($category->name == 'Uncategorized')
        {
          unset($categories[$k]);
        }
        else
        {
          $categories[$k] = array('name' => $category->name, 'slug' => $category->slug);
        }
      }
      $data['categories'] = $categories;
      $data['title'] = get_the_title();
    }
    ?>

    <?php if (is_page()): ?>
      <div class="page" id="page-<?php the_ID(); ?>">
        <?php the_content('Read the rest of this entry &raquo;'); ?>
      </div>
      <?php else: ?>

      <?php
      if ($paged==1) :
        $lastcount = 6 ;
      elseif ($paged>1) :
        $lastcount=3;
      endif;
      ?>

      <div class="post p-<?php echo $count; if ($count>2) : echo ' p-small'; endif; if ($lastclass == $lastcount) : $lastclass = 0; echo ' last'; else : $lastclass++; endif; ?>" id="post-<?php the_ID(); ?>">
        <div class="entry-genre"><a href="" title="">Genre</a><?php //the_category() ?></div>
        <?php if (is_single()): ?>
        <h2><?php the_title() ?></h2>
        <?php elseif ($count==0) : ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php endif; ?>

        <div class="entry-image">
          <?php if (!is_single()) : ?><a href="<?php the_permalink() ?>"><?php endif; ?>
            <img src="http://placekitten.com/<?php if (is_singular()) : echo '620'; elseif ($count==0||$count==1||$count==2) : echo '300'; elseif ($count>2) : echo '140'; endif; ?>/<?php if (is_singular()) : echo '440'; elseif ($count==0) : echo '360'; elseif ($count==1||$count==2) : echo '130'; elseif ($count>2) : echo '100'; endif; ?>" alt="" />
          <?php if (!is_single()) : ?></a><?php endif; ?>
          <?php if (is_single()): ?>
          <p class="wp-caption-text">This is a caption.</p>
          <div id="entry-image-box"></div>
          <div id="entry-image-box-button"><a href=""><i class="icon-resize-full"></i>Expand</a></div>
          <?php endif; ?>
        </div>

        <?php if ($count>0) : ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php endif; ?>

        <div class="entry-meta">
          <a class="author-image" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" title="<?php the_author() ?>'s articles on collecting...">
            <img src="http://placekitten.com/33/33" alt="" width="33" height="33" />
          </a>
            <span class="meta-text">
              By <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" title="<?php the_author() ?>'s articles on collecting..."><?php the_author() ?></a> | Posted <?php the_date('M d, Y') ?> at <?php the_time('g:i a') ?>
            </span>
          <div class="entry-share pull-right">
            <span class='st_email_hcount'></span>
            <span class='st_facebook_hcount'></span>
            <span class='st_twitter_hcount'></span>
            <span class='st_pinterest_hcount'></span>
          </div>
        </div>

        <div class="entry">
          <?php if (is_single()) : the_content('Read the rest of this entry &raquo;'); ?>
          <?php elseif ($count<3) : if($count==0): $length=300; else : $length=100; endif; $longString =get_the_excerpt('Read the rest of this entry &raquo;'); $truncated = substr($longString,0,strpos($longString,' ',$length)); echo '<p>'.$truncated.'... <a href="'.get_permalink().'">more</a></p>'; ?>
          <?php endif; ?>
        </div>

        <?php if ($count==0||is_single()) : ?>
        <div class="entry-footer">
          <p><?php the_tags(); ?></p>
          <div class="entry-share">
            <span class='st_sharethis_custom'>Share This</span>
          </div>
          <!--  <iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=standard&amp;show_faces=true&amp;width=728&amp;action=like&amp;font=trebuchet+ms&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:728px; height:80px;" allowTransparency="true"></iframe>
            <?php comments_popup_link('Add a comment &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> -->
        </div>
        <?php endif; ?>

      </div><!-- end .post -->

      <?php endif; ?>
    <?php $count++; ?>
    <?php endwhile; ?>

  <?php if (!is_page()): ?>
    <?php comments_template(); ?>

    <?php if(is_single()) : ?>
      <div class="permalinklink">Permalink: <a href="<?php the_permalink(); ?>"><?php the_permalink() ?></a></div>
      <?php endif; ?>

    <div class="navigation">
      <div class="lt">
        <?php echo next_posts_link('older posts') ?>
      </div>
      <div class="rt">
        <?php echo previous_posts_link('newer posts') ?>
      </div>
    </div>

    <?php if (is_front_page()||is_archive()): ?>
      <!-- <button class="btn btn-small gray-button see-more-full" id="seemore-posts" data-url="/blog/page/2" data-target=".post">
        See more
      </button>
      <a id="json_click_handler" href="#">
        Click here to do JSON request! We'll get the 10 most recent posts as JSON
      </a>


      <div id="json_response_box"></div>-->

      <?php endif; ?>

    <?php endif; ?>

  <?php else : ?>

  <h2>Not Found</h2>
  <p>Sorry, but you are looking for something that isn't here.</p>

  <?php endif; ?>

</div><!-- end #blog-contents -->

<?php $content = ob_get_clean(); ?>

<?php
ob_start();
?>



<div id="sidebar">

  <img src="/images/iab/300x250.gif">

  <h3>Tags</h3>
  <p><?php the_tags('<ul class="cf" style="list-style: none; padding: 0; margin: 0;"><li class="rounded p-tag">','</li><li class="rounded p-tag">','</li></ul>'); ?></p>

  <ul id="widgets" class="span-5">
    <li id="widget-bloggers" class="widget">
      <h3 class="widget-title">Our Bloggers</h3>

<?php
      $display_admins = false;
      $order_by = 'display_name'; // 'nicename', 'email', 'url', 'registered', 'display_name', or 'post_count'
      $role = 'author'; // 'subscriber', 'contributor', 'editor', 'author' - leave blank for 'all'
      $avatar_size = 40;
      $hide_empty = true; // hides authors with zero posts

      if(!empty($display_admins)) {
        $blogusers = get_users('orderby='.$order_by.'&role='.$role);
      } else {
        $admins = get_users('role=administrator');
        $exclude = array();
      foreach($admins as $ad) {
        $exclude[] = $ad->ID;
      }
      $exclude = implode(',', $exclude);
        $blogusers = get_users('exclude='.$exclude.'&orderby='.$order_by.'&role='.$role);
      }
      $authors = array();
      foreach ($blogusers as $bloguser) {
        $user = get_userdata($bloguser->ID);
      if(!empty($hide_empty)) {
        $numposts = count_user_posts($user->ID);
      if($numposts < 1) continue;
      }
        $authors[] = (array) $user;
      }

      echo '<ul class="author-list">';
      foreach($authors as $author) {
        $display_name = $author['data']->display_name;
        $avatar = get_avatar($author['ID'], $avatar_size);
        $author_posts_url = get_author_posts_url($author['ID']);
        $author_profile_url = get_the_author_meta( 'user_url', $author['ID'] );
        $nice_name = get_the_author_meta( 'user_nicename', $author['ID'] );
      echo '<li><a href="', $author_profile_url, '">', $avatar , '</a><strong>'.$display_name.'</strong><br /><a href="/blog/people/', $nice_name, '" class="author-link">[Bio]</a> <a href="', $author_posts_url, '" class="contributor-link">[Articles]</a></li>';
      echo '';

      }
      echo '</ul>'; ?>
  </li>

  <li id="widget-other-news" class="widget">

    <div class="row-fluid sidebar-title">
      <div class="span8">
        <h3 class="Chivo webfont" style="visibility: visible;">In Other News</h3>
      </div>
      <div class="span4 text-right">
        <a href="/blog" class="text-v-middle link-align">See all news »</a>&nbsp;
      </div>
    </div>


      <?php if (is_single()) : $offset = 0; else : $offset = 7; endif; ?>
      <?php query_posts('offset='.$offset.'&showposts=3'); ?>

      <?php while (have_posts()) : the_post(); ?>
      <div class="row-fluid">
        <h4 style="margin-bottom: 5px;">
          <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        </h4>
      </div>

      <span class="content"><?php $length=100; $longString=get_the_excerpt('...more'); $truncated = substr($longString,0,strpos($longString,' ',$length)); echo '<p>'.$truncated.'... <a href="'.get_permalink().'">more</a></p>'; ?>
</span>

      <small style="font-size: 80%">posted by <?php the_author_posts_link() ?> <span style="color: grey"><?php the_date() ?></span></small>

      <?php endwhile;?>






  </li>

    <!-- Blog Sidebar //-->
  </ul>

</div><!-- end #sidebar -->



<?php

get_sidebar();

get_footer();


$sidebar = ob_get_clean();



ob_start();

wp_footer();

$wpfooter = ob_get_clean();

?>

<?php
$key = md5(serialize($data));

if (function_exists('xcache_set'))
{
  xcache_set($key, $data, 10);
}
else
{
  zend_shm_cache_store($key, $data, 10);
}

switch (SF_ENV)
{
  case 'dev':
    $domain = 'collectorsquest.dev';
    break;
  case 'stg':
    $domain = 'cqstaging.com';
    break;
  case 'next':
    $domain = 'cqnext.com';
    break;
  case 'prod':
  default:
    $domain = 'collectorsquest.com';
    break;
}

$layout = file_get_contents(
  "http://www.collectorsquest.next/_blog/index?_session_id=". $_COOKIE['legacy'] ."&key=". $key .'&env='. SF_ENV
);

echo str_replace(
  array('<!-- Blog Head //-->', '<!-- Blog Content //-->', '<!-- Blog Sidebar //-->', '<!-- WP Footer //-->'),
  array($head, $content, $sidebar, $wpfooter),
  $layout
);



/**
 * Initialization. Add our script if needed on this page.
 */
function cq_is_init() {
  global $wp_query;

  // Add code to index pages.
  if( !is_singular() ) {
    // Queue JS and CSS
    wp_enqueue_script(
      'cq-load-posts',
      '/wp-content/themes/collectorsquest/js/load-posts.js',
      array('jquery'),
      '1.0',
      true
    );

    // What page are we on? And what is the pages limit?
    $max = $wp_query->max_num_pages;
    $paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;

    // Add some parameters for the JS.
    wp_localize_script(
      'cq-load-posts',
      'cq',
      array(
        'startPage' => $paged,
        'maxPages' => $max,
        'nextLink' => next_posts($max, false)
      )
    );

  }
}
add_action('template_redirect', 'cq_is_init');






?>
