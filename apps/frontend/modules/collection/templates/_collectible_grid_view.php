<?php
  /* @var $collectible Collectible */
  $lazy_image = !isset($lazy_image) || $lazy_image ? 'lazy' : '';
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view"
     data-id="<?= $collectible->getId(); ?>"
     class="collectible_grid_view fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?= link_to_collectible($collectible, 'text', array('link_to' => array('class' => 'target'))); ?>
    </p>
  </div>
  <?php
    echo link_to_collectible($collectible, 'image', array(
      'image_tag' => array('width' => 190, 'height' => 150, 'class' => $lazy_image),
      'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
