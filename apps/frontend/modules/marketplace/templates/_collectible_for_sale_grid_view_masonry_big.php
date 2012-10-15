<?php
/**
 * @var $collectible_for_sale CollectibleForSale
 */
?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectibleId(); ?>_grid_view_masonry_big"
     data-id="<?= $collectible_for_sale->getCollectibleId(); ?>"
     class="collectible_for_sale_grid_view_masonry_big link">

  <?php
    echo link_to_collectible($collectible_for_sale->getCollectible(), 'image', array(
      'image_tag' => array('width' => 190, 'height' => 150, 'class' => 'mosaic-backdrop')
    ));
  ?>
  <div class="mosaic-overlay">
    <p><?= link_to_collectible($collectible_for_sale->getCollectible(), 'text'); ?></p>
  </div>
</div>
