<?php
  /**
   * @var $collectible Collectible
   */
  use_javascript('jquery/mosaic.js');
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view"
     data-id="<?= $collectible->getId(); ?>"
     class="collectible_grid_view">
  <?= link_to_collectible($collectible, 'image', array('width' => 190, 'height' => 150, 'class' => 'mosaic-backdrop')); ?>
  <div class="mosaic-overlay">
    <p><?= link_to_collectible($collectible, 'text'); ?></p>
  </div>
</div>

<script>
$(document).ready(function()
{
  $('.collectible_grid_view').mosaic({
    animation: 'slide'
  });
});
</script>
