<?php

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
?>

<?php ob_start(); ?>
<?php get_header(); ?>

<div id="blog-contents">

<?php if (have_posts()) : ?>

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
    <div class="post" id="post-<?php the_ID(); ?>">
      <?php if (!is_single()): ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php endif; ?>
      <div class="info">
        <?php if (is_single()): ?>
        <div style="float: right; margin: 5px 0 5px 10px;">
          <span class="st_twitter_hcount" displayText="Tweet"></span><span class="st_facebook_hcount" displayText="Share"></span>
        </div>
        <?php endif; ?>
        <span class="date"><?php the_time('m.d.y') ?> &nbsp; <a href="/blog/author/<?php the_author_login(); ?>" alt="Click for Bio" title="Click for Bio">by <?php the_author() ?></a></span>
        <div class="fixed">&nbsp;</div>
      </div>
      <div class="entry">
        <?php the_content('Read the rest of this entry &raquo;'); ?>
      </div>

      <p><?php the_tags(); ?></p>
      <iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=standard&amp;show_faces=true&amp;width=728&amp;action=like&amp;font=trebuchet+ms&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:728px; height:80px;" allowTransparency="true"></iframe>

      <a href="<?php the_permalink(); ?>">Permalink</a> |
      <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('Add a comment &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
    </div>
    <div class="clear">&nbsp;</div>
    <?php endif; ?>

  <?php endwhile; ?>

  <?php if (!is_page()): ?>

    <?php comments_template(); ?>

    <div class="navigation">
      <div class="lt">
        <?php echo next_posts_link('older posts') ?>
      </div>
      <div class="rt">
        <?php echo previous_posts_link('newer posts') ?>
      </div>
    </div>
  <?php endif; ?>
<?php else : ?>

  <h2>Not Found</h2>
  <p>Sorry, but you are looking for something that isn't here.</p>

<?php endif; ?>
</div>

<?php get_footer(); ?>
<?php $content = ob_get_clean(); ?>

<?php
  ob_start();
  get_sidebar();
  $sidebar = ob_get_clean();
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
      $domain = 'collectorsquest.stg';
      break;
    case 'prod':
    default:
      $domain = 'collectorsquest.com';
      break;
  }

  $layout = file_get_contents(
    "http://www.". $domain ."/_blog/index?_session_id=". $_COOKIE['legacy'] ."&key=". $key .'&env='. SF_ENV
  );

  echo str_replace(
    array('<!-- Blog Content //-->', '<!-- Blog Sidebar //-->'),
    array($content, $sidebar),
    $layout
  );
?>
