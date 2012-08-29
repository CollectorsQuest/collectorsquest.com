<?php
/**
 * @var $collection CollectorCollection
 * @var $sf_user    cqFrontendUser
 */

/** @var $height stdClass */
$height = $sf_user->getFlash('height_main_div', null, true, 'internal');

// Make sure we are backwards compatible to the old behavior
if (!property_exists($height, 'value') || $height->value <= 0)
{
  $height = new stdClass();
  $height->value = PHP_INT_MAX;
}

?>

<?php
  include_component(
    '_sidebar', 'widgetManageCollection',
    array(
      'collection' => $collection,
      'fallback' => array('cq_dart_slot', array('300x250', 'collections', 'collection', 'sidebar')),
      'height' => &$height
    )
  );
?>
<?php $height->value -= 250; ?>

<?php
  include_component(
    '_sidebar', 'widgetCollector',
    array(
      'collector' => $collection->getCollector(),
      'collection' => $collection,
      'limit' => 3, 'message' => true,
      'height' => &$height
    )
  );
?>

<?php
  include_component(
     '_sidebar', 'widgetTags',
    array(
      'collection' => $collection,
      'height' => &$height
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collection' => $collection, 'limit' => 3,
      'fallback' => 'random',
      'height' => &$height
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array(
      'collection' => $collection, 'limit' => 5,
      'fallback' => 'random', 'height' => &$height
    )
  );
?>
