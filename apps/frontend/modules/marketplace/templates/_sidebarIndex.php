<?php cq_dart_slot('300x250', 'market', 'landing', 'sidebar') ?>

<?php include_component('_sidebar', 'widgetMarketplaceCategories'); ?>
<?php include_component('_sidebar', 'widgetFeaturedSellers', array('title' => 'Spotlight')); ?>

<?php
  echo link_to(
    image_tag('banners/040412_CQ_Market_blue.gif'),
    '@legacy_seller_signup'
  );
?>
