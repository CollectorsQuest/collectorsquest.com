<?php
/**
 * @var $collectible Collectible
 */
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_tall"
     data-id="<?= $collectible->getId(); ?>"
     class="span3 collectible_grid_view_tall fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?= link_to_collectible($collectible, 'text', array('class' => 'target', 'truncate' => 40)); ?>
    </p>
  </div>

  <?php
    echo link_to_collectible($collectible, 'image', array(
      'image_tag' => array('width' => 140, 'height' => 295),
      'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
