<?php
/**
 * @var $sf_user  cqFrontendUser
 * @var $height stdClass
 */

cq_dart_slot('300x250', 'market', 'landing', 'sidebar');
$height->value -= 250;
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

<?php
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
    );
    $height->value -= 110;
  }
?>

<?php include_component('_sidebar', 'widgetCollections', array('limit' => 8, 'height' => &$height)); ?>
