<?php cq_ad_slot('300x250', 300, 250) ?>

<br/>
<?php include_component('_sidebar', 'widgetTags', array('collection' => $collection)); ?>

<?php
  include_component(
    '_sidebar', 'widgetCollector',
    array('collector' => $collection->getCollector(), 'collection' => $collection, 'limit' => 3)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetRelatedCollections',
    array('collection' => $collection, 'limit' => 5)
  );
?>
