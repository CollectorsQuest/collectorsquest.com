<?php
  cq_dart_slot(
    '300x250', 'market',
    isset($category) && $category instanceof ContentCategory ? $category->getSlug() : 'landing',
    'sidebar'
  );
?>

<?php include_component('_sidebar', 'widgetMarketplaceCategories'); ?>

<?php
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      '@misc_guide_to_collecting'
    );
  }
?>

<?php include_component('_sidebar', 'widgetCollections', array('limit' => 8)); ?>

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
