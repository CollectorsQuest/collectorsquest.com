<?php
  /**
   * @var $collectible Collectible
   */
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_square"
     data-id="<?= $collectible->getId(); ?>" class="span4 collectible_grid_view_square"
     style="margin-bottom: 15px;">

  <?= ice_image_tag_placeholder('190x190', array('class' => 'mosaic-backdrop')); ?>
  <?php link_to_collectible($collectible, 'image', array('width' => 190, 'height' => 190, 'class' => 'mosaic-backdrop')); ?>
  <p style="margin-top: 10px; overflow: hidden; height: 18px;">
    <?= link_to_collectible($collectible, 'text', array('class' => 'target', 'truncate' => 30)); ?>
  </p>
</div>

<script>
$(document).ready(function()
{
  $(".collectible_grid_view_square a.target").bigTarget({
    hoverClass: 'over',
    clickZone : 'div:eq(0)'
  });
});
</script>
