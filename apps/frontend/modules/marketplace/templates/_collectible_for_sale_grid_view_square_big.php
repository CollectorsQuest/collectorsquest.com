<?php

/* @var $collectible_for_sale CollectibleForSale */
/* @var $sf_request cqWebRequest */
/* @var $sf_params sfParameterHolder */

$lazy_image = !isset($lazy_image) || $lazy_image;
$lazy_image = $lazy_image && $sf_request->isLazyLoadEnabled();

/* @var $url string */
$url = !empty($url) ? $url : url_for_collectible($collectible_for_sale->getCollectible());

?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectibleId(); ?>_grid_view_square_big"
     data-id="<?= $collectible_for_sale->getCollectibleId(); ?>"
     class="span6 collectible_for_sale_grid_view_square_big">

  <a href="<?= $url; ?>" class="zoom-zone">
    <div class="collectible-info">
      <div style="padding: 10px;">
        <?= $collectible_for_sale->getCollectible()->getName(); ?><br/>
        <span class="price">
          <?php // @todo remove this once we have all items for sale ready in Frank's Picks ?>
          <?php if ($collectible_for_sale->isForSale()): ?>
            <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
          <?php endif; ?>
        </span>
      </div>
    </div>

    <?php
      echo image_tag_collectible(
        $collectible_for_sale->getCollectible(), '295x295',
        array('class' => $lazy_image ? 'lazy' : '')
      );
    ?>
  </a>
</div>
