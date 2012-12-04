<?php
/**
 * @var $sf_user  cqFrontendUser
 * @var $height stdClass
 */
?>

<?php
  if (!$sf_user->isAuthenticated())
  {
    cq_ad_slot(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png',
        array(
          'width' => '300', 'height' => '90', 'class' => 'mobile-optimized-300 center',
          'alt' => 'Quest Your Best: The Essential Guide to Collecting'
        )
      ),
      '@misc_guide_to_collecting'
    );
    $height->value -= 110;
  }
?>

<?php
  if (isset($category) && $category instanceof ContentCategory)
  {
    include_component(
      '_sidebar', 'widgetMarketplaceCategories',
      array('current_category' => $category, 'height' => &$height)
    );
  }
?>

<?php include_component('_sidebar', 'widgetMarketplaceExplore', array('height' => &$height)); ?>
