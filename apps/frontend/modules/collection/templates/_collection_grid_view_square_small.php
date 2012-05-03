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
      <?= link_to_collection($collection, 'text', array('class' => 'target', 'truncate' => 30)); ?>
    </p>
  </div>
  <?php
    echo link_to_collection(
      $collection, 'image',
      array('width' => 140, 'height' => 140, 'class' => 'mosaic-backdrop')
    );
  ?>
</div>
