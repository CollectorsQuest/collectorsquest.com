<?php cq_dart_slot('300x250', 'market', 'categories', 'sidebar') ?>

<?php include_component('_sidebar', 'widgetFeaturedSellers', array('title' => 'Spotlight')); ?>

<?php
  echo link_to(
    cq_image_tag('banners/040412_CQ_Market_blue.gif'),
    '@seller_signup'
  );
?>
