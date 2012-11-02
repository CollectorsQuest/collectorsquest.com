<?php

/* @var $collectible_for_sale CollectibleForSale */
/* @var $sf_request cqWebRequest */
/* @var $sf_params sfParameterHolder */

/**
 * We do not want to use lazy image loading when we have:
 *  1) infinite scroll
 *  2) an Ajax request
 */
$lazy_image = !isset($lazy_image) || $lazy_image;
$lazy_image = $lazy_image && !$sf_request->isXmlHttpRequest() && 'all' !== $sf_params->get('show');

/* @var $url string */
$url = !empty($url) ? $url : url_for_collectible($collectible_for_sale->getCollectible());

?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectibleId(); ?>_grid_view_square_big"
     data-id="<?= $collectible_for_sale->getCollectibleId(); ?>"
     class="span6 collectible_for_sale_grid_view_square_big">

  <a href="<?= $url; ?>" class="zoom-zone">
    <div class="collectible-info">
      <?= $collectible_for_sale->getCollectible()->getName(); ?><br/>
      <span class="price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </span>
    </div>

    <?php
      echo image_tag_collectible(
        $collectible_for_sale->getCollectible(), '295x295',
        array('class' => $lazy_image ? 'lazy' : '')
      );
    ?>
  </a>
</div>
