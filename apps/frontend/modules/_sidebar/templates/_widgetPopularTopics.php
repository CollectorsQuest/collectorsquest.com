<?php cq_sidebar_title('Topics', null); ?>

<div class="twocolumn cf">
  <ul>
  <?php
    /** @var $featured_items wpPost[] */
    foreach ($featured_items as $i => $wp_post)
    {
      // display 'regular' popular categories
      echo '<li>', link_to($wp_post->getPostTitle(), 'wordpress_featured_items', $wp_post), '</li>';
    }
  ?>
  </ul>
</div>
