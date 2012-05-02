<?php
/**
 * @var $collection Collection
 * @var $i integer
 */
?>

<div id="collection_<?= $collection->getId(); ?>_grid_view"
     data-id="<?= $collection->getId(); ?>"
     class="collection_grid_view fade-white link">

  <div class="stack">
    <div class="mosaic-overlay">
      <p class="details">
        <?php echo link_to_collection($collection, 'text', array('class' => 'target')); ?>&nbsp;<span style="color:#ccc;">(<?php echo (int) $collection->countCollectibles(); ?>)</span>&nbsp;<?php if ($collection->countCollectiblesSince('7 day ago') > 0) echo image_tag('icons/new.png'); ?>
        <br><small>by</small>
        <?php echo link_to_collector($collection->getCollector(), 'text', array('style' => 'color: #000;')); ?>
      </p>
    </div>
    <?= ice_image_tag_flickholdr('175x138', array('class' => 'mosaic-backdrop', 'i' => $collection->getId())); ?>
    <?php link_to_collection($collection, 'image', array('class' => 'mosaic-backdrop')); ?>
  </div>
</div>
