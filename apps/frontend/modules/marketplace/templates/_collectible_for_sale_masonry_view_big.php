<?php
/**
 * @var $collectible_for_sale CollectibleForSale
 * @var $url                  string
 * @var $link_parameters      string
 */
?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectibleId(); ?>_grid_view_masonry_big"
     data-id="<?= $collectible_for_sale->getCollectibleId(); ?>"
     class="span4 collectible_for_sale_grid_view_masonry_big brick link">

  <?php $default_url = url_for_collectible($collectible_for_sale->getCollectible()); ?>

  <a href="<?= $url ?: $default_url ?>" <?= $link_parameters ?: 'class="target"' ?>>
    <?php echo ice_image_tag_flickholdr('220x'. ($collectible_for_sale->getCollectibleId() % 500)); ?>
  </a>

  <div class="collectible-info">
    <a href="<?= $url ?: $default_url ?>" <?= $link_parameters ?: 'class="target"' ?>>
      <?= $collectible_for_sale->getCollectible()->getName(); ?><br/>
      <span class="price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </span>
    </a>
  </div>

</div>
