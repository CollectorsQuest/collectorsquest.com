<?php
/**
 * @var $sf_user  cqFrontendUser
 * @var $height stdClass
 */
$height = $sf_user->getFlash('height_main_div', null, true, 'internal');

// Make sure we are backwords compatible to the old behavior
if (!property_exists($height, 'value') || $height->value <= 0)
{
  $height = new stdClass();
  $height->value = PHP_INT_MAX;
}
?>

<?php
  cq_dart_slot(
    '300x250', 'market',
    isset($category) && $category instanceof ContentCategory ? $category->getSlug() : 'landing',
    'sidebar'
  );
?>

<?php $height->value -= 250; ?>

<?php include_component('_sidebar', 'widgetMarketplaceCategories', array('height' => &$height)); ?>

<?php
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      '@misc_guide_to_collecting'
    );
    $height->value -= 110;
  }
?>

<?php include_component('_sidebar', 'widgetCollections', array('limit' => 8, 'height' => &$height)); ?>

<?php include_component('_sidebar', 'widgetCollectiblesForSale', array('limit' => 3, 'height' => &$height)); ?>

<?php
/**
  <?php include_component('_sidebar', 'widgetFeaturedSellers', array('title' => 'Spotlight')); ?>

  <div class="spacer-20">
  <?php
    echo link_to(
      cq_image_tag('headlines/040412_CQ_Market_blue.gif'),
      '@seller_signup'
    );
  ?>
  </div>
**/
