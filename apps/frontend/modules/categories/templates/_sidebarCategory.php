<?php
/**
 * @var $category ContentCategory
 */
?>

<?php cq_dart_slot('300x250', 'collections', $category->getSlug(), 'sidebar') ?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('category' => $category,'limit' => 3)
  );
?>
<?php
  include_component(
    '_sidebar', 'widgetBlogPosts',
    array('category' => $category, 'limit' => 2)
  );
?>
<?php
  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('category' => $category, 'limit' => 3)
  );
?>
