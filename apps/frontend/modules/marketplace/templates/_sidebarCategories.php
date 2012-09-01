<?php cq_dart_slot('300x250', 'market', 'categories', 'sidebar') ?>
<?php $height->value -= 250; ?>

<?php
  if (IceGateKeeper::open('marketplace_categories', 'page'))
  {
    include_component(
      '_sidebar', 'widgetMarketplaceCategories',
      array('current_category' => isset($category) ? $category : null, 'height' => &$height)
    );
  }
  else
  {
    include_component(
      '_sidebar', 'widgetMarketplaceExplore',
      array('category' =>  isset($category) ? $category : null, 'height' => &$height)
    );
  }
?>

<?php
  echo cq_link_to(
    cq_image_tag('headlines/040412_CQ_Market_blue.gif', array('class' => 'spacer-top-25')),
    'blog_page', array('slug' => 'cq-faqs/guide-selling', '_decode' => 1)
  );
?>
