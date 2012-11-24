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
$lazy_image = $lazy_image && !$sf_request->isXmlHttpRequest() && 'all' !== $sf_params->get('show');
if ($sf_request->isMobile() === true)
{
  $lazy_image = null;
}

?>

<div class="span3 thumbnail link">
  <?php
    echo link_to_collectible(
      $collectible, 'image', array(
        'image_tag' => array(
          'width' => 150, 'height' => 150, 'max_width' => 132,
          'max_height' => 132, 'class' => $lazy_image ? 'lazy' : ''
        )
      )
    );
  ?>
  <p>
    <?php
      echo link_to_collectible($collectible, 'text', array(
        'truncate' => 20,
        'link_to' => array('class' => 'target')
      ));
    ?>
  </p>
</div>
