<h2><?= __('You May Also Like...'); ?></h2>
<?php foreach ($collections as $collection): ?>
  <div id="sidebar_collection_<?php echo  $collection->getId(); ?>" class="span-6 collection last">
    <div class="stack">
      <?php echo  link_to_collection($collection, 'image', array('width' => 50, 'height' => 50)); ?>
    </div>
    <div class="caption">
      <?php
        echo sprintf(
          '%s by %s',
          link_to_collection($collection, 'text', array('truncate' => 50)),
          link_to_collector($collection, 'text', array('truncate' => 17))
        );
      ?>
    </div>
  </div>
  <br clear="all">
<?php endforeach; ?>
<br class="clear"><br>
