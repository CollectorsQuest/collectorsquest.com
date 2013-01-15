<?php
/* @var $category    ContentCategory  */
/* @var $sf_user     cqFrontendUser   */
/* @var $sf_request  cqWebRequest     */

if (cqGateKeeper::open('expose_collection_categories'))
{
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
    }
  }

  include_component(
    '_sidebar', 'widgetCollectionSubCategories',
    array(
      'current_category' => $category,
      'fallback' => 'widgetCollectionCategories', 'level' => 1
    )
  );

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('category' => $category,'limit' => 4)
  );
}
else
{
  cq_dart_slot('300x250', 'collections', $category->getSlug(), 'sidebar');

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('category' => $category,'limit' => 4)
  );

  include_component(
    '_sidebar', 'widgetBlogPosts',
    array('category' => $category, 'limit' => 2)
  );

  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('category' => $category, 'limit' => 5)
  );
}
