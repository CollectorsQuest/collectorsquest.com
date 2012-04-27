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
  <?= ice_image_tag_flickholdr('190x150', array('class' => 'mosaic-backdrop', 'i' => $collection->getId())); ?>
  <?php link_to_collection($collection, 'image', array('class' => 'mosaic-backdrop')); ?>
</div>
