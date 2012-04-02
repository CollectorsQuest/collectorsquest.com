<?php
  /**
   * @var $collectible_for_sale CollectibleForSale
   */
  use_javascript('jquery/mosaic.js');
?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectibleId(); ?>_grid_view"
     data-id="<?= $collectible_for_sale->getCollectibleId(); ?>"
     class="collectible_grid_view" style="margin-bottom: 25px;">

  <?= ice_image_tag_placeholder('190x150', array('class' => 'mosaic-backdrop')); ?>
  <?php link_to_collectible($collectible_for_sale->getCollectible(), 'image', array('width' => 190, 'height' => 150, 'class' => 'mosaic-backdrop')); ?>
  <div class="mosaic-overlay">
    <p><?= link_to_collectible($collectible_for_sale->getCollectible(), 'text'); ?></p>
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
