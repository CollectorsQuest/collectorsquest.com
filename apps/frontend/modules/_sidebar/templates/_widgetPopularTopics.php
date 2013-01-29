<?php cq_sidebar_title('Trending Topics', null); ?>

<div class="twocolumn cf">
  <ul>
  <?php
    /** @var $featured_items wpPost[] */
    foreach ($featured_items as $i => $wp_post)
    {
      if ($wp_post->getId() == 34711)
      {
        echo '<li>', link_to('Railroadiana', '@aetn_mwba_railroadiana'), '</li>';
      }

      // display 'regular' popular categories
      echo '<li>', link_to($wp_post->getPostTitle(), 'wordpress_featured_items', $wp_post), '</li>';
    }
  ?>
  </ul>
</div>
