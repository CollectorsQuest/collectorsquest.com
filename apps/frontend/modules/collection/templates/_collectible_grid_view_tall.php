<?php

/* @var $collectible Collectible */
/* @var $sf_request cqWebRequest */
/* @var $sf_params sfParameterHolder */

/**
 * We do not want to use lazy image loading when we have:
 *  1) infinite scroll
 *  2) an Ajax request
 *  3) request comes from a mobile device
 */
$lazy_image = !isset($lazy_image) || $lazy_image;
$lazy_image = $lazy_image && !$sf_request->isMobile() && !$sf_request->isXmlHttpRequest() && 'all' !== $sf_params->get('show');

?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_tall"
     data-id="<?= $collectible->getId(); ?>"
     class="span3 collectible_grid_view_tall fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?php
        echo !empty($link) ?
          $link : link_to_collectible($collectible, 'text', array('class' => 'target', 'truncate' => 40));
      ?>
    </p>
  </div>

  <?php
    echo link_to_collectible($collectible, 'image', array(
      'image_tag' => array('width' => 140, 'height' => 295, 'class' => $lazy_image ? 'lazy' : ''),
      'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
