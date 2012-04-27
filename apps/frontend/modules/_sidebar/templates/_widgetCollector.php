<?php cq_sidebar_title(sprintf('About %s', $collector->getDisplayName()), null); ?>

<div class="row-fluid">
  <div class="span3">
    <?= link_to_collector($collector, 'image', array('width' => 60, 'height' => 60)); ?>
  </div>
  <div class="span8">
    <?= link_to_collector($collector, 'text'); ?>
    <?php echo sprintf(
      __('is %s %s collector'),
      in_array(strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'an' : 'a',
      '<i>'. $collector->getCollectorType() .'</i>'
    ); ?>
    <p style="margin-top: 10px;">
      <?= link_to('Send a message &raquo;', 'homepage', array('to' => $collector->getId())); ?>
    </p>
  </div>
</div>

<br/>
<div>
  Other collections by <?= $collector; ?><br/>
  <?= link_to('View all collections &raquo;', 'collections_by_collector', $collector); ?>
</div>

<?php foreach ($collections as $collection): ?>
  <div style="border: 1px solid #dcd7d7; margin-top: 10px;">
    <div style="border: 1px solid #f2f1f1; padding: 10px;">
    <p><?= link_to_collection($collection, 'text'); ?></p>
    <?php
      $c = new Criteria();
      $c->setLimit(4);
      foreach ($collection->getCollectionCollectibles($c) as $i => $collectible)
      {
        $options = array('width' => 60, 'height' => 60, 'style' => 'margin-right: 12px;');

        if ($i == 3) unset($options['style']);
        echo link_to(image_tag_collectible($collectible, '75x75', $options), 'collectible_by_slug', $collectible);
      }
    ?>
      </div>
  </div>
<?php endforeach; ?>
