<?php
  cq_dart_slot(
    '300x250', 'market',
    isset($category) && $category instanceof ContentCategory ? $category->getSlug() : 'landing',
    'sidebar'
  );
?>

<?php include_component('_sidebar', 'widgetMarketplaceCategories'); ?>
<?php include_component('_sidebar', 'widgetFeaturedSellers', array('title' => 'Spotlight')); ?>

<div class="spacer-20">
<?php
  echo link_to(
    image_tag('banners/040412_CQ_Market_blue.gif'),
    '@legacy_seller_signup'
  );
?>
</div>
