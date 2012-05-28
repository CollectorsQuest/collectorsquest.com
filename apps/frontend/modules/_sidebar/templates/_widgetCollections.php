<?php cq_sidebar_title('Collections of Interest') ?>

<?php foreach ($collections as $collection): ?>
  <div id="sidebar_collection_<?php echo  $collection->getId(); ?>" class="row-fluid link">
    <div class="span3" style="text-align: center">
      <?= link_to_collection($collection, 'image', array('width' => 50, 'height' => 50)); ?>
    </div>
    <div class="span9">
      <?= link_to_collection($collection, 'text', array('class' => 'target')); ?>
      <br/>by <?= link_to_collector($collection, 'text'); ?>
    </div>
  </div>
  <br clear="all">
<?php endforeach; ?>
