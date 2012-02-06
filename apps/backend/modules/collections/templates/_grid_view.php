<?php /** @var Collection $collection */ ?>

<?php use_stylesheet('backend/collections'); ?>

<div id="grid_view_collection_<?php echo $collection->getId(); ?>" class="span4 grid_view_collection" style="float: left;">
  <div class="stack">
    <?php echo image_tag_collection($collection); ?>
  </div>
  <p class="caption">
    <?php echo $collection->getName(); ?>&nbsp;<font style="color:#ccc;">(<?php echo (int) $collection->countCollectibles(); ?>)</font>&nbsp;<?php if ($collection->countCollectiblesSince('7 day ago') > 0) echo image_tag('icons/new.png'); ?>
    <br><small>by</small>
    <?php echo $collection->getCollector(); ?>
  </p>
</div>
