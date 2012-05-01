<?php
/**
 * @var $collectible Collectible
 */
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_square_small"
     data-id="<?= $collectible->getId(); ?>"
     class="span3 collectible_grid_view_square_small fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?= link_to_collectible($collectible, 'text', array('class' => 'target', 'truncate' => 30)); ?>
    </p>
  </div>
  <?= link_to_collectible($collectible, 'image', array('width' => 140, 'height' => 140, 'class' => 'mosaic-backdrop')); ?>
</div>
