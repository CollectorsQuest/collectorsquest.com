<?php
  use_stylesheet('legacy/collections.css');
  use_stylesheet('legacy/collectors.css');
/* @var $collector Collector */
?>

<div id="grid_view_collector_<?php echo $collector->getId(); ?>"
     class="span-8 grid_view_collector <?php echo (isset($class)) ? $class : null; ?>"
     style="<?= (isset($style)) ? $style : null; ?>">

  <div class="clearfix last">
    <div style="padding: 5px; background: #F5F8DD">
      <?php if ($iso3166 = $collector->getProfile()->getCountryIso3166()): ?>
        <div style="float: right"><?php echo image_tag('icons/flags/'.strtolower($iso3166).'.png') ?></div>
      <?php endif; ?>
      <?php echo link_to_collector($collector, 'text'); ?>
    </div>
    <div style="height: 128px; width: 100px; float: left; margin: 10px;">
      <?php echo link_to_collector($collector, 'image'); ?>
      <div style="font-size: 18px; color: #fff; background: <?php echo $collector->getProfile()->getCollectorTypeColor(); ?>;">
        &nbsp;<?php echo $collector->getProfile()->getCollectorType(); ?>
      </div>
    </div>
    <div style="margin-top: 10px; height: 100px;">
      From <?php echo $collector->getProfile() ? $collector->getProfile()->getAddress():''?>
    </div>
  </div>
  <div style="border-top: 1px dotted #E2E2E2;">
    <?php $collections = $collector->getRecentCollections(4); ?>
    <?php foreach ($collections as $i => $collection): ?>
    <div class="collection" style="float: left; margin-top: -9px;">
      <div class="stack <?php echo ($i == 3) ? 'last' : null; ?>" style="<?php echo ($i > 0) ? 'margin-left: 1px;' : null; ?>">
        <?= link_to_collection($collection, 'image', array('width' => 50, 'height' => 50)); ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
