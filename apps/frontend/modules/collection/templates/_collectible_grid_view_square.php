<?php
/**
 * @var $collectible Collectible
 */
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_square"
     data-id="<?= $collectible->getId(); ?>"
     class="span4 collectible_grid_view_square link">

  <?= link_to_collectible($collectible, 'image', array('width' => 190, 'height' => 190)); ?>
  <p><?= link_to_collectible($collectible, 'text', array('class' => 'target')); ?></p>
</div>
