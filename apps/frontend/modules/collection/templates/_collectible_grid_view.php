<?php
  /**
   * @var $collectible Collectible
   */
  use_javascript('jquery/mosaic.js');
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view"
     data-id="<?= $collectible->getId(); ?>"
     class="collectible_grid_view" style="margin-bottom: 25px;">

  <?= ice_image_tag_placeholder('190x150', array('class' => 'mosaic-backdrop')); ?>
  <?php link_to_collectible($collectible, 'image', array('width' => 190, 'height' => 150, 'class' => 'mosaic-backdrop')); ?>
  <div class="mosaic-overlay">
    <p><?= link_to_collectible($collectible, 'text', array('class' => 'target')); ?></p>
  </div>
</div>

<script>
$(document).ready(function()
{
  $('.collectible_grid_view').mosaic({
    animation: 'slide'
  });
  $(".mosaic-overlay a.target").bigTarget({
    hoverClass: 'over',
    clickZone : 'div:eq(1)'
  });
});
</script>
