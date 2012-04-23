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
wp_head(); ?>

<style>
  .search-header input, .search-header textarea { /* add to main.less*/
    width: 200px;
    -webkit-border-top-left-radius: 7px;
    -webkit-border-bottom-left-radius: 7px;
    -moz-border-radius-topleft: 7px;
    -moz-border-radius-bottomleft: 7px;
    border-top-left-radius: 7px;
    border-bottom-left-radius: 7px;
    padding-top: 7px;
    padding-bottom: 7px;
    margin-bottom: 0;
    border: 1px solid #C3C7CB;
    -webkit-box-shadow: inset 2px 2px 2px 2px rgba(0, 0, 0, 0.07);
    -moz-box-shadow: inset 2px 2px 2px 2px rgba(0, 0, 0, 0.07);
    box-shadow: inset 2px 2px 2px 2px rgba(0, 0, 0, 0.07);
    -webkit-box-shadow: inset 2px 2px 2px 2px rgba(0, 0, 0, 0.07);
    -moz-box-shadow: inset 2px 2px 2px 2px rgba(0, 0, 0, 0.07);
    box-shadow: inset 2px 2px 2px 2px rgba(0, 0, 0, 0.07);
  }


</style>


<?php
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
<?php else: ?>
<div class="row-fluid header-bar">
  <div class="span11">
    <h1 class="Chivo webfont" style="visibility: visible; ">Latest News</h1>
  </div>
</div>
<?php endif; ?>


<div id="blog-contents" class="<?php if(is_singular()): echo 'singular'; else : echo 'not-singular'; endif; ?>">
  <?php if (have_posts()) : ?>
  <?php $count = 0; ?>
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

      <div class="post p-<?php echo $count; if ($count>2) : echo ' p-small'; endif; if ($count %4 == 0): echo ' last'; endif; ?>" id="post-<?php the_ID(); ?>">
        <div class="entry-genre"><a href="" title="">Genre</a><?php //the_category() ?></div>
        <?php if (is_single()): ?>
        <h2><?php the_title() ?></h2>
        <?php elseif ($count==0) : ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php endif; ?>

        <div class="entry-image">
          <img src="http://placekitten.com/<?php if (is_singular()) : echo '620'; elseif ($count==0||$count==1||$count==2) : echo '300'; elseif ($count>2) : echo '140'; endif; ?>/<?php if (is_singular()) : echo '440'; elseif ($count==0) : echo '360'; elseif ($count==1||$count==2) : echo '130'; elseif ($count>2) : echo '100'; endif; ?>" alt="" />
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

    <!-- <div class="navigation">
      <div class="lt">
        <?php echo next_posts_link('older posts') ?>
      </div>
      <div class="rt">
        <?php echo previous_posts_link('newer posts') ?>
      </div>
    </div> -->

    <?php if (is_front_page()||is_archive()): ?>
      <!-- <button class="btn btn-small gray-button see-more-full" id="seemore-posts" data-url="/blog/page/2" data-target=".post">
        See more
      </button>-->
      <a id="json_click_handler" href="#">
        Click here to do JSON request! We'll get the 10 most recent posts as JSON
      </a>


      <div id="json_response_box"></div>

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
      <ul>
        <li class="alex-rice">
          <a href="/blog/people/alex-rice">
            <img src="/images/blog/avatar-alex-rice.png" alt="Alex Rice" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Alex Rice</strong> <br>
          <a href="/blog/people/alex-rice" title="Bio of blogger Alex Rice">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=19" title="Alex Rice's articles on collecting...">[articles]</a>
          <br clear="all">
        </li>
        <li class="brian-rubin">
          <a href="/blog/people/brian-rubin">
            <img src="/images/blog/avatar-brian-rubin.png" alt="Brian Rubin" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Brian Rubin</strong> <br>
          <a href="/blog/people/brian-rubin" title="Bio of blogger Brian Rubin">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=14" title="Brian Rubin's articles on collecting...">[articles]</a>
          <br clear="all">
        </li>
        <li class="collin-david">
          <a href="/blog/people/collin-david" title="Bio of blogger Collin David">
            <img src="/images/blog/avatar-collin-david.png" alt="Collin David" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Collin David</strong> <br>
          <a href="/blog/people/collin-david" title="Bio of blogger Collin David">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=7" title="Collin David's articles on collecting...">[articles]</a>
          <br clear="all">
        </li>
        <li class="dean-ferber">
          <a href="/blog/people/dean-ferber">
            <img src="/images/blog/avatar-dean-ferber.png" alt="Dean Ferber" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Dean Ferber</strong> <br>
          <a href="/blog/people/dean-ferber">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=9">[articles]</a>
          <br clear="all">
        </li>
        <li class="deanna-dahlsad">
          <a href="/blog/people/deanna-dahlsad">
            <img src="/images/blog/avatar-deanna-dahlsad.png" alt="Deanna Dahlsad" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Deanna Dahlsad</strong> <br>
          <a href="/blog/people/deanna-dahlsad">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=3">[articles]</a>
          <br clear="all">
        </li>
        <li class="derek-dahlsad">
          <a href="/blog/people/derek-dahlsad">
            <img src="/images/blog/avatar-derek-dahlsad.png" alt="Derek Dahlsad" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Derek Dahlsad</strong> <br>
          <a href="/blog/people/derek-dahlsad">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=4">[articles]</a>
          <br clear="all">
        </li>
        <li class="joe-szilvagyi">
          <a href="/blog/people/joe-szilvagyi">
            <img src="/images/blog/avatar-joe-szilvagyi.png" alt="Joe Szilvagyi" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Joe Szilvagyi</strong> <br>
          <a href="/blog/people/joe-szilvagyi" title="Bio of blogger Joe Szilvagyi">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=17" title="Joe Szilvagyi's articles on collecting...">[articles]</a>
          <br clear="all">
        </li>
        <li class="shawn-hennessy">
          <a href="/blog/people/shawn-hennessy/">
            <img src="/images/blog/avatar-shawn-hennessy.png" alt="M. S. Hennessy" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">M. S. Hennessy</strong> <br>
          <a href="/blog/people/shawn-hennessy/" title="Bio of blogger M. S. Hennessy">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=18" title="M. S. Hennessy's articles on collecting...">[articles]</a>
          <br clear="all">
        </li>
        <li class="tom-peeling">
          <a href="/blog/people/tom-peeling">
            <img src="/images/blog/avatar-tom-peeling.png" alt="Tom Peeling" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Tom Peeling</strong> <br>
          <a href="/blog/people/tom-peeling" title="Bio of blogger Tom Peeling">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=15" title="Tom Peeling's articles on collecting...">[articles]</a>
          <br clear="all">
        </li>
        <li class="val-ubell">
          <a href="/blog/people/val-ubell">
            <img src="/images/blog/avatar-val-ubell.png" alt="Val Ubell" align="left" style="height: 40px; margin-right: 10px; border: 1px solid #BDB7BD;">
          </a>
          <strong style="font-size: 14px;">Val Ubell</strong> <br>
          <a href="/blog/people/val-ubell">[bio]</a> &nbsp;
          <a href="/blog/index.php?author=8">[articles]</a>
          <br clear="all">
        </li>
      </ul>
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



?>
