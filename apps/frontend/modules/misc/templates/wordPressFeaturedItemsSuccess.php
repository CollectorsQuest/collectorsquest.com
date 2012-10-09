<?php
/**
 * @var $number_of_collectibles integer
 */
cq_page_title(
    $wp_post->getPostTitle(), null,
    array('class' => 'row-fluid header-bar')
  );
?>
<div class="spacer-bottom-15">
  <?= cq_image_tag($wp_post->getPostThumbnail('original'), array('alt' => $wp_post->getPostTitle())); ?>
</div>

<p class="truncate js-hide">
  <?= nl2br($wp_post->getPostContent()); ?>
</p>

<br/>
<div class="row" style="margin-left: -12px;">
  <div id="collectibles" class="row-content">
  <?php
    if ($number_of_collectibles > 0)
    {
      foreach ($pager->getResults() as $i => $collectible)
      {
        // Show the collectible (in grid, list or hybrid view)
        include_partial(
          'collection/collectible_grid_view_square_small',
          array('collectible' => $collectible, 'i' => (int) $i)
        );
      }
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

<script>
$(document).ready(function ()
{
  $('.truncate').expander({
    slicePoint: <?= strlen($wp_post->getPostExcerpt()) ?: 500; ?>,
    widow: 2,
    expandEffect: 'show',
    expandText: 'Read More',
    expandPrefix: '',
    userCollapseText: '[^]',
    onSlice: function() { $(this).show(); }
  });
});
</script>
