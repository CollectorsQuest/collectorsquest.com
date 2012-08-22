<?php
  cq_page_title(
    $wp_post->getPostTitle(), null,
    array('class' => 'row-fluid header-bar')
  );
?>
<div class="spacer-bottom-15">
  <?= cq_image_tag($wp_post->getPostThumbnail('original'), array('alt' => $wp_post->getPostTitle())); ?>
</div>

<p class="text-justify">
  <?= $wp_post->getPostContent(); ?>
</p>

<br/>
<div class="row" style="margin-left: -12px;">
  <div id="collectibles" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $collectible)
    {
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_grid_view_square_small',
        array('collectible' => $collectible, 'i' => (int) $i)
      );
    }
  ?>
  </div>
</div>

<div class="row-fluid text-center">
<?php
  include_component(
    'global', 'pagination', array('pager' => $pager)
  );
?>
</div>
