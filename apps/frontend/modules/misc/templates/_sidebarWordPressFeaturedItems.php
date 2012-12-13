<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'collectibles', 'sidebar') ?>
</div>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('wp_post' => $wp_post, 'limit' => 8)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetBlogPosts',
    array('ids' => $wp_post_ids, 'limit' => 2)
  );
?>
