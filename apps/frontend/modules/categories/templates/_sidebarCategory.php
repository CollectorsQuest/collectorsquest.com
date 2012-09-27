<?php
/**
 * @var $category ContentCategory
 */

if (IceGateKeeper::open('expose_collection_categories'))
{
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png'),
      'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
    );
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
    array('category' => $category,'limit' => 5)
  );
}
else
{
  cq_dart_slot('300x250', 'collections', $category->getSlug(), 'sidebar');

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('category' => $category,'limit' => 5)
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
