<?php
/* @var $category ContentCategory  */
/* @var $sf_user  cqFrontendUser   */
/* @var $seller   Collector        */
/* @var $height   stdClass         */
/* @var $sf_request   cqWebRequest */
?>

<?php
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
            'width' => '300', 'height' => '90', 'class' => 'mobile-optimized-300 center',
            'alt' => 'Quest Your Best: The Essential Guide to Collecting'
          )
        ),
        '@misc_guide_to_collecting'
      );
      $height->value -= 110;
    }
  }
  else if ((!$seller = $sf_user->getSeller(true)) || ($seller && !$seller->hasBoughtCredits()))
  {
    if (!$sf_request->isMobileLayout())
    {
      cq_ad_slot(
        cq_image_tag('headlines/040412_CQ_Market_blue.gif',
          array(
            'width' => '300', 'height' => '250',
            'alt' => 'How can I sell my items?'
          )
        ),
        url_for('blog_page', array('slug' => 'cq-faqs/guide-selling', '_decode' => 1))
      );
    }
  }

  $height->value -= 250;
?>


<?php
  if (cqGateKeeper::open('expose_market_categories'))
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
