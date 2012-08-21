<div class="spacer-bottom-15">
  <?= cq_image_tag($wp_post->getPostThumbnail('original'), array('alt' => $wp_post->getPostTitle())); ?>
</div>

<p class="text-justify">
  <?= $wp_post->getPostContent(); ?>
</p>

<?php
  cq_page_title(
    $wp_post->getPostTitle(), null,
    array('class' => 'row-fluid header-bar spacer-bottom-15')
  );
?>

<div class="row">
  <div id="collectibles" class="row-content">
  <?php
    /** @var $collectibles Collectible[] */
    foreach ($collectibles as $i => $collectible)
    {
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_grid_view_square',
        array('collectible' => $collectible, 'i' => (int) $i)
      );
    }
  ?>
  </div>
</div>
