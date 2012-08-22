<?php
/**
 * @var $collection Collection
 */
?>

<div id="collection_<?= $collection->getId(); ?>_grid_view_square_small"
     data-id="<?= $collection->getId(); ?>"
     class="span3 collection_grid_view_square_small fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?= link_to_collection($collection, 'text', array('link_to' => array('class' => 'target'))); ?>
    </p>
  </div>
  <?php
    echo link_to_collection($collection, 'image', array(
      'image_tag' => array('width' => 140, 'height' => 140),
      'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
