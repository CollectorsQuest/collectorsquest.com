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
$lazy_image = $lazy_image && !$sf_request->isXmlHttpRequest() && 'all' !== $sf_params->get('show')

?>

<div id="collectible_for_sale_<?= $collectible_for_sale->getCollectibleId(); ?>_grid_view_square_big"
     data-id="<?= $collectible_for_sale->getCollectibleId(); ?>"
     class="span6 collectible_for_sale_grid_view_square_big fade-white link">

  <div class="text-box">
    <a href="<?= $url ?: $default_url ?>" <?= $link_parameters ?: 'class="target"' ?>>
      <?= $collectible_for_sale->getCollectible()->getName(); ?><br/>
      <span class="price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </span>
    </a>
  </div>

  <?php
    echo link_to_collectible($collectible_for_sale->getCollectible(), 'image', array(
      'image_tag' => array('width' => 295, 'height' => 295, 'class' => $lazy_image ? 'lazy' : ''),
      'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
