<?php
/**
 * @var $sf_user  cqFrontendUser
 * @var $seller   Collector
 * @var $height   stdClass
 */
?>

<?php
  if (($seller = $sf_user->getSeller(true)) && !$seller->hasBoughtCredits())
  {
    echo cq_link_to(
      cq_image_tag('headlines/040412_CQ_Market_blue.gif', array('class' => 'spacer-top-25')),
      'blog_page', array('slug' => 'cq-faqs/guide-selling', '_decode' => 1)
    );
  }
  else
  {
    cq_dart_slot('300x250', 'market', 'categories');
  }

  $height->value -= 250;
?>


<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('limit' => 8, 'height' => &$height)
  );
?>
