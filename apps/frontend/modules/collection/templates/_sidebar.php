<?php cq_dart_slot('300x250', 'collections', 'collection', 'sidebar') ?>

<?php
  include_component(
    '_sidebar', 'widgetCollector',
    array(
      'collector' => $collection->getCollector(),
      'collection' => $collection,
      'limit' => 3, 'message' => true
    )
  );
?>

<?php include_component('_sidebar', 'widgetTags', array('collection' => $collection)); ?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collection' => $collection, 'limit' => 5)
  );
?>
