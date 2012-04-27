<?php
/**
 * @var $collectible_for_sale CollectibleForSale
 */
?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectorId(); ?>_grid_view_square"
     data-id="<?= $collectible_for_sale->getCollectorId(); ?>"
     class="span4 collectible_for_sale_grid_view_square link">

  <?= link_to_collectible($collectible_for_sale->getCollectible(), 'image', array('width' => 190, 'height' => 190)); ?>
  <p>
    <?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target', 'truncate' => 20)); ?>
    <strong class="pull-right"><?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?></strong>
  </p>
</div>
