<?php
  /**
   * @var $collection Collection
   * @var $i integer
   */
  use_javascript('jquery/mosaic.js');
  use_stylesheet('frontend/mosaic.css');
?>

<div id="collection_<?= $collection->getId(); ?>_grid_view"
     data-id="<?= $collection->getId(); ?>"
     class="collection_grid_view fade-white">

  <div class="mosaic-overlay">
    <p class="details">
      <?php echo link_to_collection($collection, 'text', array('class' => 'target')); ?>&nbsp;<span style="color:#ccc;">(<?php echo (int) $collection->countCollectibles(); ?>)</span>&nbsp;<?php if ($collection->countCollectiblesSince('7 day ago') > 0) echo image_tag('icons/new.png'); ?>
      <br><small>by</small>
      <?php echo link_to_collector($collection->getCollector(), 'text', array('style' => 'color: #000;')); ?>
    </p>
  </div>
  <?= ice_image_tag_flickholdr('190x150', array('class' => 'mosaic-backdrop', 'i' => $i)); ?>
  <?php link_to_collection($collection, 'image', array('class' => 'mosaic-backdrop')); ?>
</div>

<script>
  $(document).ready(function()
  {
    $('.fade-white').mosaic();
    $(".mosaic-overlay a.target").bigTarget({
      hoverClass: 'over',
      clickZone : 'div:eq(0)'
    });
  });
</script>
