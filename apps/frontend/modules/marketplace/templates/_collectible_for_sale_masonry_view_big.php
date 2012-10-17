<?php
/**
 * @var $collectible_for_sale CollectibleForSale
 */
?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectibleId(); ?>_grid_view_masonry_big"
     data-id="<?= $collectible_for_sale->getCollectibleId(); ?>"
     class="span4 collectible_for_sale_grid_view_masonry_big brick link"
     style="width: 220px; margin-left: 16px; margin-bottom: 20px;">

  <?php echo ice_image_tag_flickholdr('220x'. ($collectible_for_sale->getCollectibleId() % 500)); ?>

</div>
