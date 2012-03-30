<?php
  /**
   * @var $collection Collection
   */
  use_javascript('jquery/mosaic.js');
  use_stylesheet('frontend/mosaic.css');
?>

<div id="collection_<?= $collection->getId(); ?>_grid_view"
     data-id="<?= $collection->getId(); ?>" class="collection_grid_view">

  <div class="stack">
    <?= ice_image_tag_placeholder('180x142', array('class' => 'mosaic-backdrop')); ?>
    <?php link_to_collection($collection, 'image', array('class' => 'mosaic-backdrop')); ?>
    <div class="mosaic-overlay">
      <p class="details">
        <?php echo link_to_collection($collection, 'text'); ?>&nbsp;<font style="color:#ccc;">(<?php echo (int) $collection->countCollectibles(); ?>)</font>&nbsp;<?php if ($collection->countCollectiblesSince('7 day ago') > 0) echo image_tag('icons/new.png'); ?>
        <br><small>by</small>
        <?php echo link_to_collector($collection->getCollector(), 'text', array('style' => 'color: #000;')); ?>
      </p>
    </div>
  </div>

</div>

<script>
  $(document).ready(function()
  {
    $('.collection_grid_view .stack').mosaic({
      animation: 'slide'
    });
  });
</script>
