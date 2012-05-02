<?php
/**
 * @var $collectible Collectible
 */
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view"
     data-id="<?= $collectible->getId(); ?>"
     class="collectible_grid_view link">

  <?php
    echo link_to_collectible(
      $collectible, 'image',
      array('width' => 190, 'height' => 150, 'class' => 'mosaic-backdrop')
    );
  ?>
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
});
</script>
