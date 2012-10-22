<?php
/**
 * @var $collectible_for_sale CollectibleForSale
 */
?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectibleId(); ?>_grid_view_masonry_big"
     data-id="<?= $collectible_for_sale->getCollectibleId(); ?>"
     class="span4 collectible_for_sale_grid_view_masonry_big brick link">

  <?php echo ice_image_tag_flickholdr('220x'. ($collectible_for_sale->getCollectibleId() % 500)); ?>

  <div class="text-box">
    <?php
      echo !empty($link) ?
        $link : link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target'));
    ?><br/>
    <span class="price">
      <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
    </span>
  </div>
</div>
