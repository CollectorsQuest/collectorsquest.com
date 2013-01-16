<?php
/* @var $sf_request   cqWebRequest */

cq_dart_slot('300x250', 'collections', null, 'sidebar');
?>

<?php include_component('_sidebar', 'widgetCollectiblesForSale', array('limit' => 4, 'fallback' => 'random')); ?>

<?php include_component('_sidebar', 'widgetCollections', array('limit' => 4, 'fallback' => 'random')); ?>

<?php
  /* @var $sf_user cqFrontendUser */
  if (!$sf_user->isAuthenticated())
  {
    if ($sf_request->isMobileLayout())
    {
      cq_ad_slot(
        cq_image_tag('headlines/2012-06-24_CQGuidePromo_635x111.png',
          array(
            'width' => '635', 'height' => '111', 'class' => 'spacer-top-20',
            'alt' => 'Quest Your Best: The Essential Guide to Collecting'
          )
        ),
        '@misc_guide_to_collecting'
      );
    }
    else
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
  }
?>

<?php include_component('_sidebar', 'widgetMagnifyVideos', array('limit' => 3)); ?>

<?php include_component('_sidebar', 'widgetBlogPosts', array('limit' => 3)); ?>


