<li>
<div id="grid_view_collection_<?php echo $collection->getId(); ?>"
     data-id="<?php echo $collection->getId(); ?>"
     class="span-5 grid_view_collection <?php echo (isset($class)) ? $class : null; ?>"
     style="<?= (isset($style)) ? $style : null; ?>">

  <div class="stack">
    <?php echo link_to_collection($collection, 'image'); ?>
  </div>
  <p class="caption">
    <?php echo link_to_collection($collection, 'text'); ?>&nbsp;<font style="color:#ccc;">(<?php echo (int) $collection->countCollectibles(); ?>)</font>&nbsp;<?php if ($collection->countCollectiblesSince('7 day ago') > 0) echo image_tag('icons/new.png'); ?>
    <br><small>by</small>
    <?php echo link_to_collector($collection->getCollector(), 'text', array('style' => 'color: #000;')); ?>
  </p>
</div>
</li>
