<?php
/**
 * @var $collection CollectorCollection
 */
  $content_count = $collection->collectionContentCount();
?>

<?php
  include_component(
    '_sidebar', 'widgetManageCollection',
    array(
      'collection' => $collection,
      'fallback' => array('cq_dart_slot', array('300x250', 'collections', 'collection', 'sidebar'))
    )
  );
?>

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
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collection' => $collection, 'limit' => 3,
      'fallback' => 'random',
      'content_count' => $content_count
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array(
      'collection' => $collection, 'limit' => 5,
      'fallback' => 'random',
  	  'content_count' => $content_count
    )
  );
?>
