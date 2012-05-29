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

  if (is_category()) {
    $data['category'] = $wp_query->get_queried_object()->name;
  }
  else if (is_tag()) {
    $data['tag'] = $wp_query->get_queried_object()->name;
  }

  if (function_exists('bcn_display'))
  {
    $data['breadcrumbs'] = bcn_display(true);
  }
  else if (file_exists(__DIR__ .'/../../plugins/wordpress-seo/frontend/class-breadcrumbs.php'))
  {
    require_once __DIR__ .'/../../plugins/wordpress-seo/frontend/class-breadcrumbs.php';

    $wpseo_bc = new WPSEO_Breadcrumbs();
    $data['breadcrumbs'] = $wpseo_bc->breadcrumb(null, null, false);
  }
  else
  {
    $home = array('name' => 'Blog', 'url' => '/blog');
    $url = $_SERVER["REQUEST_URI"];
    $crumbs = array($home);

    if (is_tag()) {
      $crumbs[] = array('name' => 'Tag Archive: '. single_tag_title("", false), $url);
    }
    elseif (is_category()) {
      $crumbs[] = array('name' => 'Category Archive: '. single_cat_title("", false), $url);
    }
    elseif (is_single()) {
      $crumbs[] = array(
        'name' => get_the_author_meta('display_name'),
        'url' => '/blog/author/'. get_the_author_meta('nicename')
      );
      $crumbs[] = array('name' => get_the_title(), 'url' => null);
    }
    elseif (is_author()) {
      $crumbs[] = array('name' => 'Archive for '. get_the_author_meta('display_name'), $url);
    }
    elseif (is_day()) {
      $crumbs[] = array('name' => "Archive for ". the_time('F jS, Y'), $url);
    }
    elseif (is_month()) {
      $crumbs[] = array('name' => "Archive for ". the_time('F, Y'), $url);
    }
    elseif (is_year()) {
      $crumbs[] = array('name' => "Archive for ". the_time('Y'), $url);
    }
    elseif (isset( $_GET['paged']) && !empty( $_GET['paged'])) {
      $crumbs[] = array('name' => "Blog Archives");
    }
    elseif (is_search()) {
      $crumbs[] = array('name' => "Search Results", $url);
    }

    $data['breadcrumbs'] = $crumbs;
  }

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
      <h1 class="Chivo webfont" style="visibility: visible; ">Blogger:&nbsp;&nbsp;<span><?php the_author() ?></span></h1>
    </div>
</div>

  <br />

  <!-- This sets the $curauth variable -->
  <?php $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); ?>

  <div id="author-info-box" class="row-fluid">
    <div class="author-avatar span4">
      <?php echo get_avatar(get_the_author_meta('ID'),192) //<img src="http://placekitten.com/33/33" alt="" width="33" height="33"/> ?>
    </div>
    <div class="author-bio span8">
      <?php echo apply_filters('the_content', $curauth->user_description); ?>
    </div>
  </div>
  <div class="row-fluid section-title">
    <div class="span12">
      <h2 class="Chivo webfont"><?php echo $curauth->first_name ?>'s Recent Posts</h2>
    </div>
  </div>
  <?php } elseif (is_category()) { ?>
    <div class="span11">
      <h1 class="Chivo webfont" style="visibility: visible; "><?php _e( 'Category Archive:', 'collectorsquest' ) ?>&nbsp;&nbsp;<span><?php single_cat_title() ?></span></h1>
        <?php $categorydesc = category_description(); if ( !empty($categorydesc) ) echo apply_filters( 'archive_meta', '<div class="archive-meta">' . $categorydesc . '</div>' ); ?>
    </div>
  <?php } elseif (is_tag()) { ?>
    <div class="span11">
      <h1 class="Chivo webfont" style="visibility: visible; "><?php _e( 'Related News:', 'collectorsquest' ) ?>&nbsp;&nbsp;<span><?php single_tag_title() ?></span></h1>
      <?php $categorydesc = category_description(); if ( !empty($categorydesc) ) echo apply_filters( 'archive_meta', '<div class="archive-meta">' . $categorydesc . '</div>' ); ?>
    </div>
  <?php } elseif (is_search()) { ?>
  <div class="span11">
    <h1 class="Chivo webfont" style="visibility: visible; "><?php _e( 'Search Results for:', 'collectorsquest' ) ?>&nbsp;&nbsp;<span><?php the_search_query(); ?></span></h1>
    <?php $categorydesc = category_description(); if ( !empty($categorydesc) ) echo apply_filters( 'archive_meta', '<div class="archive-meta">' . $categorydesc . '</div>' ); ?>
  </div>
<?php } elseif (is_404()) { ?>
<!-- <div class="span11">
  <h1 class="Chivo webfont" style="visibility: visible; "><?php _e( 'You are here', 'collectorsquest' ) ?></h1>
</div> -->
<?php } ?>

<?php if (!is_author()) : ?>
</div>

<br />
<?php endif; ?>

<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$count = ($paged > 1) ? 8 : 1;
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

        ?> row-fluid" id="post-<?php the_ID(); ?>">

        <?php if (is_single()) : ?>
          <!-- <div class="entry-genre"><a href="" title=""><?php the_category() ?></a></div> -->

          <h2 class="entry-title"><?php the_title() ?></h2>
        <?php endif; ?>

        <div class="entry-image <?php if (is_front_page() && $count==1): echo "span6"; elseif (!is_single()) : echo  "span3"; endif; ?>">

          <?php

          ?>

          <?php
          if (is_single()) :
            $size = 'blog-single-p1';
            //$size = array(620,440);

            $img_w = 620;
            $img_h = 440;
          elseif (is_front_page() && $count == 1) :
            $size = 'blog-homepage-p1';
            //$size = array(300,300);
          else :
            //$size = 'blog-homepage-thumb';
            $size = 'thumbnail';
            //$size = array(140,140);
          endif;
          ?>

          <?php if (!is_single()) : ?>
            <a href="<?php the_permalink() ?>">
          <?php endif; ?>

            <?php $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'full' ); ?>

            <?php if (is_single() && $image_attributes[1] >= 620) : ?>
                  <?php
                    global $custom_thumbs_mb;
                    $custom_thumbs_mb->the_field('cq_thumb_align');
                    $ct = $custom_thumbs_mb->get_the_value();
                    if ($ct == "top") { $a="t"; }
                    elseif ($ct == "middle") { $a="m"; }
                    elseif ($ct == "bottom") { $a="b"; }
                    else { $a="t"; }
                  ?>

              <img src="/blog/wp-content/themes/collectorsquest/thumb.php?src=<?php echo get_post_image_url('full');  //echo 'http://placekitten.com/700/700'; ?>&w=<?php echo $img_w ?>&h=<?php echo $img_h ?>&zc=1&a=<?php echo $a; ?>" alt=""/>

              <?php
              $thumbnail_id = get_post_thumbnail_id($post->ID);
              $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));
              if ($thumbnail_image && isset($thumbnail_image[0]) && $thumbnail_image[0]->post_excerpt) :
                echo '<p class="wp-caption-text">'.$thumbnail_image[0]->post_excerpt.'</p>';
              endif;
              ?>

            <?php elseif (!is_single()) : ?>
            <img src="<?php echo get_post_image_url($size, $count); //'http://placekitten.com/700/700'; ?>" alt=""/>
            <?php endif; ?>

          <?php if (!is_single()) : ?>
            </a>
          <?php endif; ?>

          <?php if (is_single()) : ?>
            <div id="entry-image-box"></div>
            <div id="entry-image-box-button"><a href=""><i class="icon-resize-full"></i>Expand</a></div>
          <?php endif; ?>

        </div>

        <?php if (is_front_page() || is_archive() || is_search()) : ?>
          <!-- <div class="entry-genre"><a href="" title=""><?php the_category() ?></a></div> -->
          <h2 class="entry-title <?php if (is_front_page() && $count==1): echo "span6"; elseif (!is_single()) : echo  "span9"; endif; ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php endif; ?>

        <div class="entry-meta <?php if (is_front_page() && $count==1): echo "span6"; elseif (!is_single()) : echo  "span9"; else : echo "span12";  endif; ?>">
          <span class="meta-text">
            <a class="author-image" href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"
               title="<?php the_author() ?>'s articles on collecting...">

              <?php if (is_single()) { $size = 33; } else { $size = 16; } ?>
              <?php echo get_avatar(get_the_author_meta('ID'),$size) //<img src="http://placekitten.com/33/33" alt="" width="33" height="33"/> ?>
            </a>
            <span class="author-info">
            By <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"
                  title="<?php the_author() ?>'s articles on collecting..."><?php the_author() ?></a>
              <span class="entry-date">| Posted
                <?php
               /* global $post;
                  $postdate = get_the_date('mdy');
                  $date = date('mdy');
                  if ($date == $postdate ||
                    date('mdy',strtotime($date." -1 day")) == $postdate ||
                    date('mdy',strtotime($date." -2 days")) == $postdate) :
                    echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';
                  else :
                  endif;*/
                    echo get_the_date('M jS, Y');

                ?>
              </span>
              <?php edit_post_link('Edit', ' | ', ''); ?>
            </span>
          </span>

          <?php if (is_single()) : ?>
          <div class="entry-share pull-right share <?php if (is_front_page() && $count==1): echo "span6"; endif; ?>">
            <!-- AddThis Button BEGIN -->
            <a class="btn btn-lightblue btn-mini-social addthis_button_email at300b" title="Email" href="#"><i class="mail-icon-mini"></i> Email</a>
            <a fb:like:width="40" fb:like:layout="button_count" class="addthis_button_facebook_like at300b" title="Send to Facebook_like" href="#"><fb:like href="http://www.cqnext.com/collection/2679/black-african-american-action-figures" font="arial" action="like" show_faces="false" layout="button_count" width="40" ref=".T8Tk3VPCHxM.like" class=" fb_edge_widget_with_comment fb_iframe_widget"><span style="height: 20px; width: 90px;"><iframe scrolling="no" id="f32bafee7cc112a" name="f9a28977dfae9a" style="border: medium none; overflow: hidden; height: 20px; width: 90px;" title="Like this content on Facebook." class="fb_ltr " src="http://www.facebook.com/plugins/like.php?action=like&amp;channel_url=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D6%23cb%3Df1f2e05567d97ca%26origin%3Dhttp%253A%252F%252Fwww.cqnext.com%252Ffa15af1ebd0d86%26domain%3Dwww.cqnext.com%26relation%3Dparent.parent&amp;extended_social_context=false&amp;font=arial&amp;href=http%3A%2F%2Fwww.cqnext.com%2Fcollection%2F2679%2Fblack-african-american-action-figures&amp;layout=button_count&amp;locale=en_US&amp;node_type=link&amp;ref=.T8Tk3VPCHxM.like&amp;sdk=joey&amp;show_faces=false&amp;width=90"></iframe></span></fb:like></a>
            <a tw:twitter:data-count="none" class="addthis_button_tweet at300b" title="Tweet" href="#"><iframe scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/tweet_button.1337330192.html#_=1338303710642&amp;count=horizontal&amp;counturl=http%3A%2F%2Fwww.cqnext.com%2Fcollection%2F2679%2Fblack-african-american-action-figures&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=http%3A%2F%2Fwww.cqnext.com%2Fcollection%2F2679%2Fblack-african-american-action-figures&amp;size=m&amp;text=Interactive%20Community%20and%20Marketplace%20for%20Collectors%20-%20CollectorsQuest.com%3A&amp;url=http%3A%2F%2Fwww.cqnext.com%2Fcollection%2F2679%2Fblack-african-american-action-figures%23.T8Tk3Zr4zH8.twitter" class="twitter-share-button twitter-count-horizontal" style="width: 107px; height: 20px;" title="Twitter Tweet Button"></iframe></a>
            <a g:plusone:annotation="none" g:plusone:size="medium" class="addthis_button_google_plusone at300b" title="Send to Google_plusone" href="#"><div id="___plusone_0" style="height: 20px; width: 32px; display: inline-block; text-indent: 0pt; margin: 0pt; padding: 0pt; background: none repeat scroll 0% 0% transparent; border-style: none; float: none; line-height: normal; font-size: 1px; vertical-align: baseline;"><iframe width="100%" scrolling="no" frameborder="0" allowtransparency="true" hspace="0" marginheight="0" marginwidth="0" style="position: static; top: 0pt; width: 32px; margin: 0px; border-style: none; height: 20px; left: 0pt; visibility: visible;" tabindex="0" vspace="0" id="I0_1338303710745" name="I0_1338303710745" src="https://plusone.google.com/_/+1/fastbutton?bsv=pr&amp;url=http%3A%2F%2Fwww.cqnext.com%2Fcollection%2F2679%2Fblack-african-american-action-figures&amp;size=medium&amp;count=true&amp;annotation=none&amp;hl=en-US&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fgapi%2F__features__%2Frt%3Dj%2Fver%3DCWE_IPcQhZo.en_US.%2Fsv%3D1%2Fam%3D!gFuVl93svF6I1poz5g%2Fd%3D1%2Frs%3DAItRSTN26r926UvdupK16BCuhGUPc4J-XA#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart&amp;id=I0_1338303710745&amp;parent=http%3A%2F%2Fwww.cqnext.com" title="+1"></iframe></div></a>
            <a original.png"="" collectible="" multimedia="" frontend="" images="" pi:pinit:media="&lt;img src=" class="addthis_button_pinterest_pinit at300b" title="Send to Pinterest_pinit" href="#"><iframe scrolling="no" frameborder="0" style="width:49px; height:26px;" scrollbars="no" allowtransparency="true" role="presentation" src="//s7.addthis.com/static/r07/pinit004.html?url=http%253A%252F%252Fwww.cqnext.com%252Fcollection%252F2679%252Fblack-african-american-action-figures%2523.T8Tk3fb7hew.pinterest&amp;media=%3Cimg%20src%3D&amp;description=&amp;ats=imp_url%3D1%26url%3Dhttp%25253A%25252F%25252Fwww.cqnext.com%25252Fcollection%25252F2679%25252Fblack-african-american-action-figures%252523.T8Tk3fb7hew.pinterest%26title%3DInteractive%2520Community%2520and%2520Marketplace%2520for%2520Collectors%2520-%2520CollectorsQuest.com%26url_transforms%3Ddefrag%253D1%26passthrough%3D&amp;atc=username%3Dra-4fa2c6240b775d05"></iframe></a>
            <!-- AddThis Button END -->
          </div>
          <?php endif; ?>
        </div>


        <div class="entry-content <?php if (is_front_page() && $count==1): echo "span6"; elseif (!is_single()) : echo "span9"; else : echo "span12"; endif; ?>">
          <?php
          if (is_single()) :
             the_content();
          elseif (is_front_page() || is_archive() || is_search()) :

            if (is_front_page() && $count == 1) :
              cq_excerpt('cq_excerptlength_firstpost');
            else :
              cq_excerpt('cq_excerptlength_archive');
            endif;

          endif;
          ?>
        </div>

        <?php if ((is_front_page() && $count == 1) /* is_single()*/) : ?>
        <div class="entry-footer <?php if (is_front_page() && $count==1): echo "span6"; else : echo "span12"; endif; ?>">
        <?php if (is_front_page() && $count == 1) : ?>
          <p><?php the_tags(); ?></p>
        <?php endif; ?>
          <!-- <div class="entry-share">
            <span class='st_sharethis_custom'>Share This</span>
          </div> -->
          <?php if (is_single()) : ?>
          <span class="meta-text">
            <a href="<?php the_permalink(); ?>">Permalink</a> |
            <?php edit_post_link('Edit', '', ''); ?>
          </span>

          <div class="entry-share pull-right">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style">
              <a class="addthis_button_email"></a>
              <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
              <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
              <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
              <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?php echo get_post_image_url(); ?>" pi:pinit:class="pin-it-button" pi:pinit:count-layout="horizontal"></a>
            </div>
            <!-- AddThis Button END -->
          </div>
          <?php endif; ?>
          <!--  <iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=standard&amp;show_faces=true&amp;width=728&amp;action=like&amp;font=trebuchet+ms&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:728px; height:80px;" allowTransparency="true"></iframe>
            <?php comments_popup_link('Add a comment &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> -->
        </div>
        <?php endif; ?>

      </div><!-- end .post -->

      <?php endif; ?>

    <?php $count++; ?>

    <?php endwhile; ?>

  <?php if (!is_page()): ?>

    <?php if (is_single()) : ?>

    <div id="comments">
    <?php comments_template(); ?>
    </div>

    <?php endif; ?>

    <?php if ( is_archive() && $wp_query->max_num_pages > 1 ) : ?>
    <div id="nav-below" class="navigation">
      <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'collectorsquest' ) ); ?></div>
      <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'collectorsquest' ) ); ?></div>
    </div><!-- #nav-below -->
    <?php else : ?>
    <!--<div id="nav-below" class="navigation">
      <div class="nav-previous"><?php previous_post_link( '%link', '<div class="header-bar footer-nav Chivo webfont">PREVIOUS</div><span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentyten' ) . '</span> %title' ); ?></div>
      <div class="nav-next"><?php next_post_link( '%link', '<div class="header-bar footer-nav Chivo webfont">NEXT</div> %title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentyten' ) . '</span>' ); ?></div>
    </div> #nav-below -->
    <?php endif; ?>


    <?php endif; ?>

  <?php else :

  $page = get_page_by_title( '404' );

  if($page) :
    echo $page->post_content;
  else :

  ?>

  <h2 class="entry-title">Not Found</h2>
  <p>"We've lost this page;<br />
    It's gone astray.<br />
    We hope you'll stay<br />
    a little more.
  </p>
  <p>
    The search above's<br />
    What you should use.<br />
    Please excuse<br />
    our 404."
  </p>

  <?php

  endif; ?>



  <?php endif; ?>

</div><!-- end #blog-contents -->

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

  $layout = str_replace(
    array('<!-- Blog Head //-->', '<!-- Blog Content //-->', '<!-- Blog Footer //-->'),
    array($head, $content, $footer),
    $layout
  );


  if (is_single()) {
    $sidebar = "singular-sidebar";
  }
  elseif (is_page()) {
    $sidebar = "static-page-sidebar";
  }
  else {
    $sidebar = "non-singular-sidebar";
  }

  $array = array();
  $widgets = get_option('sidebars_widgets');

  if (is_array($widgets[$sidebar]))
  foreach ($widgets[$sidebar] as $widget) {
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
