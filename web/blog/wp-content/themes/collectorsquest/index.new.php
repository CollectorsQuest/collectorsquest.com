<?php
  /**
   * @var $wp_query WP_Query
   */

  $data = array();

  $data['is_page'] = is_page();
  $data['is_single'] = is_single();
  $data['is_category'] = is_category();
  $data['is_tag'] = is_tag();

  if (is_category()) {
    $data['category'] = $wp_query->get_queried_object()->name;
  }
  else if (is_tag()) {
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
<?php endif; ?>

<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>

<div id="blog-contents" class="<?php if (is_front_page()) : echo ' news-front'; elseif (is_singular()) : echo 'singular'; else : echo 'not-singular'; endif; ?>">
  <?php if (have_posts()) : ?>

  <?php
    $count = ($paged > 1) ? 9 : 1;
    $lastclass = 0;

    if ($paged == 2) {
      query_posts('offset=7&posts_per_page=8');
    }
    elseif ($paged > 2) {
      query_posts('offset=' . (($paged * 8) - 9) . '&posts_per_page=8');
    }
  ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php
      if (is_single() || is_page())
      {
        $categories = get_the_category();
        foreach ($categories as $k => $category)
        {
          if ($category->name == 'Uncategorized') {
            unset($categories[$k]);
          }
          else {
            $categories[$k] = array('name' => $category->name,
                                    'slug' => $category->slug);
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
        echo $count; if ($count > 3) :
          echo ' p-small';
        endif;
        if ($lastclass == $lastcount) :
          $lastclass = 0;
          echo ' last';
        else : $lastclass++;
        endif;
        echo (++$j % 2 == 0) ? ' even' : ' odd';
        ?>" id="post-<?php the_ID(); ?>">

        <?php if (is_front_page() || is_single()) : ?><div class="entry-genre"><a href="" title="">Genre</a><?php //the_category() ?></div><?php endif; ?>

        <?php if (is_single()): ?>
          <h2><?php the_title() ?></h2>
        <?php elseif (is_front_page() && $count == 1) : ?>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php endif; ?>

        <div class="entry-image">
          <?php if (!is_single()) : ?>
            <a href="<?php the_permalink() ?>">
          <?php endif; ?>
              <img src="http://placekitten.com/<?php
                if (is_singular()) :
                  echo '620';
                elseif (is_front_page() && $count == 1 || is_front_page() && $count == 2 || is_front_page() && $count == 3) :
                  echo '300';
                elseif ($count > 3) :
                  echo '140';
                else :
                  echo '140';
                endif; ?>/<?php
                if (is_singular()) :
                  echo '440';
                elseif (is_front_page() && $count == 1) :
                  echo '360';
                elseif (is_front_page() && $count == 2 || is_front_page() && $count == 3) :
                  echo '130';
                elseif (is_front_page() && $count > 3) :
                  echo '100';
                else :
                  echo '140';
                endif;
                ?>" alt=""/>
          <?php if (!is_single()) : ?>
            </a>
          <?php endif; ?>

          <?php if (is_single()): ?>
            <p class="wp-caption-text">This is a caption.</p>
            <div id="entry-image-box"></div>
            <div id="entry-image-box-button"><a href=""><i class="icon-resize-full"></i>Expand</a></div>
          <?php endif; ?>

        </div>

        <?php if (is_archive()) : ?><div class="entry-genre"><a href="" title="">Genre</a><?php //the_category() ?></div><?php endif; ?>

        <?php if ((is_front_page() && $count > 1) || is_archive()) : ?>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php endif; ?>

        <div class="entry-meta">
          <a class="author-image" href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"
             title="<?php the_author() ?>'s articles on collecting...">
            <img src="http://placekitten.com/33/33" alt="" width="33" height="33"/>
          </a>
            <span class="meta-text">
              By <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"
                    title="<?php the_author() ?>'s articles on collecting..."><?php the_author() ?></a> | Posted <?php the_date('M d, Y') ?>
              at <?php the_time('g:i a') ?>
            </span>

          <div class="entry-share pull-right">
            <span class='st_email_hcount'></span>
            <span class='st_facebook_hcount'></span>
            <span class='st_twitter_hcount'></span>
            <span class='st_pinterest_hcount'></span>
          </div>
        </div>

        <div class="entry">
          <?php

      if (is_single()) :
         the_content();
      elseif (is_front_page()) :
        if ($count == 1) :
          $length = 300;
        elseif ($count == 2 || $count == 3) :
          $length = 100;
        endif;
        $longString = get_the_excerpt('... more');
        $truncated = substr($longString, 0, strpos($longString, ' ', $length));
        echo '<p>' . $truncated . '... <a href="' . get_permalink() . '">more</a></p>';
      else :
        $length = 200;
        $longString = get_the_excerpt('... more');
          $truncated = substr($longString, 0, strpos($longString, ' ', $length));
          echo '<p>' . $truncated . '... <a href="' . get_permalink() . '">more</a></p>';
          ?>
          <?php endif; ?>
        </div>

        <?php if ((is_front_page() && $count == 1) || is_single()) : ?>
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

  <h2>Not Found</h2>
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
