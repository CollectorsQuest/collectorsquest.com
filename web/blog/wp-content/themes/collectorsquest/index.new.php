<?php
  /**
   * @var $wp_query WP_Query
   */

  $data = array();

  $data['the_id'] = get_the_ID();
  $data['is_page'] = is_page();
  $data['is_single'] = is_single();
  $data['is_category'] = is_category();
  $data['is_tag'] = is_tag();
  $data['is_front_page'] = is_front_page();
  $data['is_author'] = is_author();

  if (is_category()) {
    $data['category'] = $wp_query->get_queried_object()->name;
  }
  else if (is_tag()) {
    $data['tag'] = $wp_query->get_queried_object()->name;
  }

  $home = array('name' => 'blog', 'url' => '/blog');
  $url = $_SERVER["REQUEST_URI"];

  if (is_front_page()) { $crumbs = array( $home ); }
  elseif (is_tag()) { $crumbs = array( $home, array('name' => 'Tag Archive: '.single_tag_title("", false), $url)); }
  elseif (is_category()) { $crumbs = array( $home, array('name' => single_cat_title("", false), $url)); }
  elseif (is_single()) { $crumbs = array( $home, array('name' => get_the_author_meta('display_name'), 'url' => '/blog/author/'.get_the_author_meta('nicename')),array('name' => get_the_title(), 'url' => null)); }
  elseif (is_author()) { $crumbs = array( $home, array('name' => 'Archive for '.get_the_author_meta('display_name'), $url)); }
  elseif (is_day()) { $crumbs = array( $home, array('name' => "Archive for ". the_time('F jS, Y'), $url)); }
  elseif (is_month()) { $crumbs = array( $home, array('name' => "Archive for ". the_time('F, Y'), $url)); }
  elseif (is_year()) { $crumbs = array( $home, array('name' => "Archive for ". the_time('Y'), $url)); }
  elseif (isset( $_GET['paged']) && !empty( $_GET['paged'])) {echo "Blog Archives"; }
  elseif (is_search()) { $crumbs = array( $home, array('name' => "Search Results", $url)); }

  $data['breadcrumbs'] = $crumbs;


  ob_start();
  wp_head();
  $head = ob_get_clean();


  ob_start();
  get_header();

?>

<div class="row-fluid header-bar">
  <?php if (is_page()) { ?>
  <div class="span11">
    <h1 class="Chivo webfont" style="visibility: visible; "><?php the_title() ?></h1>
  </div>
  <?php } elseif (is_single()) { ?>
    <div class="span7">
      <h1 class="Chivo webfont" style="visibility: visible; ">Blog Post</h1>
    </div>
    <div class="back-nav span5">
      <a href="/blog/">Back to Latest News &rarr;</a>
    </div>
  <?php } elseif (is_front_page()) { ?>
    <div class="span11">
      <h1 class="Chivo webfont" style="visibility: visible; ">Latest News</h1>
    </div>
  <?php } elseif (is_author()) { ?>
    <div class="span11">
      <h1 class="Chivo webfont" style="visibility: visible; ">Blogger: <span><?php the_author() ?></span></h1>
    </div>
</div>

  <!-- This sets the $curauth variable -->
  <?php $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); ?>

  <div id="author-info-box">
    <div class="author-avatar">
      <?php echo get_avatar(get_the_author_meta('ID'),140) //<img src="http://placekitten.com/33/33" alt="" width="33" height="33"/> ?>
    </div>
    <div class="author-bio">
      <?php echo $curauth->user_description; ?>
    </div>
  </div>
  <?php } elseif (is_category()) { ?>
    <div class="span11">
      <h1 class="Chivo webfont" style="visibility: visible; "><?php _e( 'Category Archive:', 'collectorsquest' ) ?> <span><?php single_cat_title() ?></span></h1>
        <?php $categorydesc = category_description(); if ( !empty($categorydesc) ) echo apply_filters( 'archive_meta', '<div class="archive-meta">' . $categorydesc . '</div>' ); ?>
    </div>
  <?php } elseif (is_tag()) { ?>
    <div class="span11">
      <h1 class="Chivo webfont" style="visibility: visible; "><?php _e( 'Tag Archive:', 'your-theme' ) ?> <span><?php single_tag_title() ?></span></h1>
      <?php $categorydesc = category_description(); if ( !empty($categorydesc) ) echo apply_filters( 'archive_meta', '<div class="archive-meta">' . $categorydesc . '</div>' ); ?>
    </div>
  <?php } ?>

<?php if (!is_author()) : ?>
</div>
<?php endif; ?>


<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$count = ($paged > 1) ? 9 : 1;
$lastclass = 0;
?>


<div id="blog-contents" class="<?php if (is_front_page()) : echo 'news-front'; elseif (is_singular()) : echo 'singular'; else : echo 'not-singular'; endif; ?>">

<?php if (have_posts()) : ?>

  <?php while (have_posts()) : the_post(); ?>

    <?php

      wp_reset_query(); //for ajax post loading

      if (is_single() || is_page())
      {
        $categories = get_the_category();
        foreach ($categories as $k => $category)
        {
          if ($category->name == 'Uncategorized') {
            unset($categories[$k]);
          }
          else {
            $categories[$k] = array(
              'name' => $category->name,
              'slug' => $category->slug);
          }
        }
        $data['categories'] = $categories;
        $data['title'] = get_the_title();
      }
    ?>

    <?php if (is_page()) : ?>

      <div class="page" id="page-<?php the_ID(); ?>">
        <?php the_content('Read the rest of this entry &raquo;'); ?>
      </div>

    <?php else: ?>

      <?php
        if ($paged == 1) {
          $lastcount = 6;
        }
        elseif ($paged > 1) {
          $lastcount = 3;
        }
        else {
          $lastcount = null;
        }
      ?>

      <div class="post p-<?php

        echo $count;

        if ($count > 3) {
          echo ' p-small';
        }
        if ($lastclass == $lastcount) {
          $lastclass = 0;
          echo ' last';
        }
        else {
          $lastclass++;
        }

        echo (++$e % 2 == 0) ? ' even' : ' odd';

        ?>" id="post-<?php the_ID(); ?>">

        <?php if (is_single()) : ?>
          <!-- <div class="entry-genre"><a href="" title=""><?php the_category() ?></a></div> -->

          <h2 class="entry-title"><?php the_title() ?></h2>
        <?php endif; ?>

        <div class="entry-image">

          <?php
          $image_id = get_post_thumbnail_id();
          $image_url = wp_get_attachment_image_src($image_id,'full');
          $image_url = $image_url[0];

          if (!$image_url) :
            $args = array(
              'post_parent' => $post->ID,
              'post_type' => 'attachment',
              'post_mime_type' => 'image',
              'orderby' => 'menu_order',
              'order' => 'ASC',
              'offset' => '0',
              'numberposts' => 1
            );

            $images = get_posts($args);

            if ( count( $images ) > 0 ) :
              $image_url = wp_get_attachment_url($images[0]->ID);
            else :
              $image_url = catch_that_image();
            endif;
          endif;
          ?>

          <?php
          if (is_single()) :
            $img_w = '620';
            $img_h = '440';
          elseif (is_front_page() && $count == 1) :
            $img_w = '300';
            $img_h = '300';
          else :
            $img_w = '140';
            $img_h = '140';
          endif;
          ?>

          <?php if (!is_single()) : ?>
            <a href="<?php the_permalink() ?>">
          <?php endif; ?>

            <?php $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'full' ); ?>

            <?php if (is_single() && $image_attributes[1] >= 620) : ?>
              <img src="/blog/wp-content/themes/collectorsquest/thumb.php?src=<?php echo $image_url;  //echo 'http://placekitten.com/700/700'; ?>&w=<?php echo $img_w ?>&h=<?php echo $img_h ?>&zc=1&a=t" alt=""/>
              <?php
              $thumbnail_id = get_post_thumbnail_id($post->ID);
              $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));
              if ($thumbnail_image && isset($thumbnail_image[0])) :
                echo '<p class="wp-caption-text">'.$thumbnail_image[0]->post_excerpt.'</p>';
              endif;
              ?>

            <?php elseif (is_front_page() || is_archive()) : ?>
            <img src="/blog/wp-content/themes/collectorsquest/thumb.php?src=<?php echo $image_url; //'http://placekitten.com/700/700'; ?>&w=<?php echo $img_w ?>&h=<?php echo $img_h ?>&zc=1&a=t" alt=""/>
            <?php endif; ?>

          <?php if (!is_single()) : ?>
            </a>
          <?php endif; ?>

          <?php if (is_single()) : ?>
            <div id="entry-image-box"></div>
            <div id="entry-image-box-button"><a href=""><i class="icon-resize-full"></i>Expand</a></div>
          <?php endif; ?>

        </div>

        <?php if (is_front_page() || is_archive()) : ?>
          <!-- <div class="entry-genre"><a href="" title=""><?php the_category() ?></a></div> -->
          <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php endif; ?>

        <div class="entry-meta">
          <span class="meta-text">
            <a class="author-image" href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"
               title="<?php the_author() ?>'s articles on collecting...">

              <?php if (is_single()) { $size = 33; } else { $size = 16; } ?>
              <?php echo get_avatar(get_the_author_meta('ID'),$size) //<img src="http://placekitten.com/33/33" alt="" width="33" height="33"/> ?>
            </a>
            <span class="author-info">
            By <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"
                  title="<?php the_author() ?>'s articles on collecting..."><?php the_author() ?></a> <!-- <span class="entry-date">| Posted <?php the_date('M d, Y') ?>
            at <?php the_time('g:i a') ?></span> -->
            </span>
          </span>

          <?php if (is_single()) : ?>
          <div class="entry-share pull-right">
            <!-- ShareThis Button BEGIN
            <span class='st_email_hcount'></span>
            <span class='st_facebook_hcount'></span>
            <span class='st_twitter_hcount'></span>
            <span class='st_googleplus_hcount'></span>
            <span class='st_pinterest_hcount'></span>
            ShareThis Button BEGIN -->

            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style">
              <a class="addthis_button_email"></a>
              <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
              <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
              <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
              <a class="addthis_button_pinterest_pinit" pi:pinit:media="http://YOUR-DOMAIN.com/IMAGE.jpg" pi:pinit:layout="horizontal"></a>
            </div>
            <!-- AddThis Button END -->
          </div>
          <?php endif; ?>
        </div>


        <div class="entry">
          <?php
          if (is_single()) :
             the_content();
          elseif (is_front_page()||is_archive()) :
            if ($count == 1) :
              $length = 300;
            else :
              $length = 200;
            endif;
            $longString = get_the_excerpt('... more');
            $truncated = substr($longString, 0, strpos($longString, ' ', $length));
            echo '<p>' . $truncated . '... <a href="' . get_permalink() . '">more</a></p>';
          endif;
          ?>
        </div>

        <?php if ((is_front_page() && $count == 1) || is_single()) : ?>
        <div class="entry-footer">
        <?php if (is_front_page() && $count == 1) : ?>
          <p><?php the_tags(); ?></p>
        <?php endif; ?>
          <!-- <div class="entry-share">
            <span class='st_sharethis_custom'>Share This</span>
          </div> -->
          <div class="entry-share pull-right">
            <!-- ShareThis Button BEGIN
            <span class='st_email_hcount'></span>
            <span class='st_facebook_hcount'></span>
            <span class='st_twitter_hcount'></span>
            <span class='st_googleplus_hcount'></span>
            <span class='st_pinterest_hcount'></span>
            ShareThis Button BEGIN -->

            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style">
              <a class="addthis_button_email"></a>
              <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
              <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
              <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
              <a class="addthis_button_pinterest_pinit" pi:pinit:media="http://YOUR-DOMAIN.com/IMAGE.jpg" pi:pinit:layout="horizontal"></a>
            </div>
            <!-- AddThis Button END -->
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

    <?php if (is_single()) : ?>
     <div class="permalinklink">Permalink: <a href="<?php the_permalink(); ?>"><?php the_permalink() ?></a></div>
    <?php endif; ?>

    <div class="navigation">
      <div class="lt">
        <?php next_posts_link('older posts') ?>
      </div>
      <div class="rt">
        <?php previous_posts_link('newer posts') ?>
      </div>
    </div>

    <?php endif; ?>

  <?php else : ?>

  <h2 class="entry-title">Not Found</h2>
  <p>Sorry, but you are looking for something that isn't here.</p>

  <?php endif; ?>

</div><!-- end #blog-contents -->

<?php $content = ob_get_clean(); ?>

<?php
  ob_start();
  get_sidebar('new');
  $sidebar = ob_get_clean();

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
      $domain = 'collectorsquest.next';
      break;
    case 'prod':
    default:
      $domain = 'cqnext.com';
      break;
  }

  $layout = file_get_contents(
    "http://www." . $domain . "/_blog/index?_session_id=" . $_COOKIE['cq_frontend'] . "&key=" . $key . '&env=' . SF_ENV
  );

  echo str_replace(
    array('<!-- Blog Head //-->', '<!-- Blog Content //-->', '<!-- Blog Sidebar //-->', '<!-- Blog Footer //-->'),
    array($head, $content, $sidebar, $footer),
    $layout
  );
?>
