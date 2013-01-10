<?php cq_dart_slot('300x250', 'collections', 'landing', 'sidebar') ?>

<?php include_partial('aetn/partials/franksPicksPromo_300x90', array('class' => 'spacer-top-20')); ?>

<?php include_component('_sidebar', 'widgetPopularCategories'); ?>

<?php
  /* @var $sf_user cqFrontendUser */
  if (!$sf_user->isAuthenticated())
  {
    cq_ad_slot(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png',
        array(
          'width' => '300', 'height' => '90', 'class' => 'spacer-top-20 mobile-optimized-300 center',
          'alt' => 'Quest Your Best: The Essential Guide to Collecting'
        )
      ),
      '@misc_guide_to_collecting'
    );
  }
?>

<?php
/*  include_partial(
    'marketplace/partials/holidayMarketBuyPackagePromo_300x90',
    array('class' => 'spacer-top-20')
  );*/
?>

<?php include_component('_sidebar', 'widgetMagnifyVideos'); ?>
