<?php
/**
 * @var $category ContentCategory
 */
?>

<?php cq_ad_slot('300x250', 300, 250) ?>

<?php include_component('_sidebar', 'widgetCollectiblesForSale', array('limit' => 3)); ?>
<?php include_component('_sidebar', 'widgetMagnifyVideos', array('category' => $category, 'limit' => 3)); ?>
<?php include_component('_sidebar', 'widgetBlogPosts', array('category' => $category, 'limit' => 2)); ?>
