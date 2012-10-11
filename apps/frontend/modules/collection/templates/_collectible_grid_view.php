<?php

/* @var $collectible Collectible */
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

<div id="collectible_<?= $collectible->getId(); ?>_grid_view"
     data-id="<?= $collectible->getId(); ?>"
     class="collectible_grid_view fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?= link_to_collectible($collectible, 'text', array('link_to' => array('class' => 'target'))); ?>
    </p>
  </div>
  <?php
    echo link_to_collectible($collectible, 'image', array(
      'image_tag' => array('width' => 190, 'height' => 150, 'class' => $lazy_image ? 'lazy' : ''),
      'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
