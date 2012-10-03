<?php
/**
 * @var $category ContentCategory
 * @var $sf_user  cqFrontendUser
 * @var $seller   Collector
 * @var $height   stdClass
 */
?>

<?php
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png'),
      'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
    );
    $height->value -= 110;
  }
  else if ((!$seller = $sf_user->getSeller(true)) || ($seller && !$seller->hasBoughtCredits()))
  {
    echo cq_link_to(
      cq_image_tag('headlines/040412_CQ_Market_blue.gif'),
      'blog_page', array('slug' => 'cq-faqs/guide-selling', '_decode' => 1)
    );
  }

  $height->value -= 250;
?>


<?php
  if (IceGateKeeper::open('marketplace_categories', 'page'))
  {
    include_component(
      '_sidebar', 'widgetMarketplaceCategories',
      array('current_category' => $category, 'height' => &$height)
    );
  }
  else
  {
    include_component(
      '_sidebar', 'widgetMarketplaceExplore',
      array('category' => $category, 'height' => &$height)
    );
  }
?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('category' => $category, 'limit' => 8, 'height' => &$height)
  );
?>
