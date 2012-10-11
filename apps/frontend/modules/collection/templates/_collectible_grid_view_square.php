<?php
  /* @var $collectible Collectible */
  $lazy_image = !isset($lazy_image) || $lazy_image ? 'lazy' : '';
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_square"
     data-id="<?= $collectible->getId(); ?>"
     class="span4 collectible_grid_view_square link">

  <?php
    echo link_to_collectible($collectible, 'image', array(
      'image_tag' => array('width' => 190, 'height' => 190, 'class' => $lazy_image)
    ));
  ?>
  <p><?= link_to_collectible($collectible, 'text', array('link_to' => array('class' => 'target'))); ?></p>
</div>
