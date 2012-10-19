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

  <div style="position: relative; top: 100%; height: 80px; margin-top: -80px; padding: 10px; background: #fff;">
    <?php
      echo !empty($link) ?
        $link : link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target'));
    ?><br/>
    <span class="price">
      <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
    </span>
  </div>
</div>
