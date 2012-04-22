<?php
/**
 * @var $collectible Collectible
 */
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_square"
     data-id="<?= $collectible->getId(); ?>"
     class="span4 collectible_grid_view_square link">

  <?= ice_image_tag_placeholder('190x190'); ?>
  <?php link_to_collectible($collectible, 'image', array('width' => 190, 'height' => 190)); ?>
  <p style="margin-top: 10px; overflow: hidden; height: 18px;">
    <?= link_to_collectible($collectible, 'text', array('class' => 'target', 'truncate' => 30)); ?>
  </p>
</div>
