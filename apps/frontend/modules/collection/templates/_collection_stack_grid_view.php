<?php

/* @var $collection Collection */
/* @var $sf_request cqWebRequest */
/* @var $sf_params sfParameterHolder */
/* @var $i integer */

/**
 * We do not want to use lazy image loading when we have:
 *  1) infinite scroll
 *  2) an Ajax request
 */
$lazy_image = !isset($lazy_image) || $lazy_image;
$lazy_image = $lazy_image && !$sf_request->isXmlHttpRequest() && 'all' !== $sf_params->get('show')

?>

<div id="collection_<?= $collection->getId(); ?>_grid_view"
     data-id="<?= $collection->getId(); ?>"
     class="collection_grid_view fade-white link">

  <div class="stack">
    <div class="mosaic-overlay">
      <p class="details">
        <?php
          echo sprintf(
            '%s&nbsp;<span style="color:#ccc;">(%d)</span>&nbsp;%s',
            link_to_collection($collection, 'text', array('link_to' => array('class' => 'target'))),
            $collection->getNumItems(),
            ($collection->countCollectiblesSince('7 day ago') > 0) ? cq_image_tag('icons/new.png') : null
          );
        ?>
        <br><small>by</small>
        <?php
          echo link_to_collector($collection->getCollector(), 'text', array(
            'link_to' => array('style' => 'color: #000;')
          ));
        ?>
      </p>
    </div>
    <?php
      echo link_to_collection($collection, 'image', array(
          'image_tag' => array('width' => 175, 'height' => 138, 'class' => $lazy_image ? 'lazy' : ''),
          'link_to' => array('class' => 'mosaic-backdrop')
      ));
    ?>
  </div>
</div>
