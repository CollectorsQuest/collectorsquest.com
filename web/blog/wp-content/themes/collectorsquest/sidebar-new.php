<div id="sidebar">

  <img src="/images/iab/300x250.gif">

  <div class="row-fluid sidebar-title">
    <div class="span8">
      <h3 class="Chivo webfont" style="visibility: visible;">Tags</h3>
    </div>
    <!-- <div class="span4 text-right">
      <a href="/blog" class="text-v-middle link-align">See all news »</a>&nbsp;
    </div>-->
  </div>
  <p><?php the_tags('<ul class="cf" style="list-style: none; padding: 0; margin: 0;"><li class="rounded p-tag">','</li><li class="rounded p-tag">','</li></ul>'); ?></p>

  <ul id="widgets" class="span-5">
    <li id="widget-bloggers" class="widget">
      <div class="row-fluid sidebar-title">
        <div class="span8">
          <h3 class="Chivo webfont" style="visibility: visible;">Our Bloggers</h3>
        </div>
        <!--<div class="span4 text-right">
          <a href="/blog" class="text-v-middle link-align">See all news »</a>&nbsp;
        </div>-->
      </div>

      <?php
      $display_admins = false;
      $order_by = 'display_name'; // 'nicename', 'email', 'url', 'registered', 'display_name', or 'post_count'
      $role = 'author'; // 'subscriber', 'contributor', 'editor', 'author' - leave blank for 'all'
      $avatar_size = 40;
      $hide_empty = true; // hides authors with zero posts

      if (!empty($display_admins)) {
        $blogusers = get_users('orderby=' . $order_by . '&role=' . $role);
      } else {
        $admins = get_users('role=administrator');
        $exclude = array();
        foreach ($admins as $ad) {
          $exclude[] = $ad->ID;
        }
        $exclude = implode(',', $exclude);
        $exclude = str_replace(",7", "", $exclude);
        $blogusers = get_users('exclude=' . $exclude . ',6,13,11');
      }
      $authors = array();
      foreach ($blogusers as $bloguser) {
        $user = get_userdata($bloguser->ID);
        if (!empty($hide_empty)) {
          $numposts = count_user_posts($user->ID);
          if ($numposts < 1) continue;
        }
        $authors[] = (array)$user;
      }

      echo '<ul class="author-list">';
      foreach ($authors as $author) {
        $display_name = $author['data']->display_name;
        $avatar = get_avatar($author['ID'], $avatar_size);
        $author_posts_url = get_author_posts_url($author['ID']);
        $author_profile_url = get_the_author_meta('user_url', $author['ID']);
        $nice_name = get_the_author_meta('user_nicename', $author['ID']);
        echo '<li><a href="', $author_profile_url, '">', $avatar, '</a><strong>' . $display_name . '</strong><br /><a href="/blog/people/', $nice_name, '" class="author-link">[Bio]</a> <a href="', $author_posts_url, '" class="contributor-link">[Articles]</a></li>';
        echo '';

      }
      echo '</ul>';
      ?>
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
      <?php query_posts('offset=' . $offset . '&showposts=3'); ?>

      <?php while (have_posts()) : the_post(); ?>
      <div class="row-fluid bottom-margin">
        <h4 style="margin-bottom: 5px;">
          <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        </h4>
        <span class="content">
          <?php $length=100; $longString=get_the_excerpt('...more'); $truncated = substr($longString,0,strpos($longString,' ',$length)); echo $truncated.'... ' //.'... <a href="'.get_permalink().'">more</a>'; ?>
        </span>
        <small style="font-size: 80%">posted by <?php the_author_posts_link() ?> <span style="color: grey"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span></small>
      </div>
      <?php endwhile;?>

    </li>

    <!-- Blog Sidebar //-->
  </ul>

</div><!-- end #sidebar -->
