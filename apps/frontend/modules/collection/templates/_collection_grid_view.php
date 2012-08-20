<?php
/**
 * @var $collection Collection
 * @var $i integer
 */
?>

<div id="collection_<?= $collection->getId(); ?>_grid_view"
     data-id="<?= $collection->getId(); ?>"
     class="collection_grid_view fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?= link_to_collection($collection, 'text', array('class' => 'target', 'style' => 'font-size: 130%;')); ?>
      <br/><small>by</small>
      <?= link_to_collector($collection->getCollector(), 'text', array('style' => 'color: #000;')); ?>
    </p>
  </div>
  <?php
    echo link_to_collection($collection, 'image', array(
        'image_tag' => array( 'width' => 190, 'height' => 150, 'class' => 'mosaic-backdrop'),
        'link_to' => array('class' => 'mosaic-backdrop')
      )
    );
  ?>
</div>
