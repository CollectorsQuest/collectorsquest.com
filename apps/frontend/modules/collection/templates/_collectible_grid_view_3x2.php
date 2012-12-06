<?php

/* @var $collectible Collectible */
/* @var $sf_request cqWebRequest */
/* @var $sf_params sfParameterHolder */

$lazy_image = !isset($lazy_image) || $lazy_image;
$lazy_image = $lazy_image && $sf_request->isLazyLoadEnabled();
if (isset($no_lazy_load))
{
  $lazy_image = false;
}

?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_3x2"
     data-id="<?= $collectible->getId(); ?>"
     class="span9 collectible_grid_view_3x2 fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?php
        echo !empty($link) ?
          $link : link_to_collectible($collectible, 'text', array('class' => 'target', 'truncate' => 120));
      ?>
    </p>
  </div>

  <?php
    echo link_to_collectible($collectible, 'image', array(
      'image_tag' => array('width' => 450, 'height' => 295, 'class' => $lazy_image ? 'lazy' : ''),
      'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
