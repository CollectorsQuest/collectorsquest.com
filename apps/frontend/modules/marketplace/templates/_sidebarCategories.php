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
    cq_ad_slot(
      cq_image_tag('headlines/040412_CQ_Market_blue.gif',
        array(
          'width' => '300', 'height' => '250', 'class' => 'spacer-top-25',
          'alt' => 'How can I sell my items?'
        )
      ),
      url_for('blog_page', array('slug' => 'cq-faqs/guide-selling', '_decode' => 1))
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
