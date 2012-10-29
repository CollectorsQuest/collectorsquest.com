<?php cq_dart_slot('300x250', 'collections', null, 'sidebar') ?>

<?php include_component('_sidebar', 'widgetCollectiblesForSale', array('limit' => 4, 'fallback' => 'random')); ?>

<?php include_component('_sidebar', 'widgetCollections', array('limit' => 4, 'fallback' => 'random')); ?>

<?php
  /* @var $sf_user cqFrontendUser */
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
    );
  }
?>

<?php include_component('_sidebar', 'widgetMagnifyVideos', array('limit' => 3)); ?>

<?php include_component('_sidebar', 'widgetBlogPosts', array('limit' => 3)); ?>


