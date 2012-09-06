<?php
/**
 * @var $category ContentCategory
 */

if (IceGateKeeper::open('expose_collection_categories'))
{
  include_component(
    '_sidebar', 'widgetCollectionSubCategories',
    array(
      'current_category' => $category,
      'fallback' => 'widgetCollectionCategories', 'level' => 1
    )
  );

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('category' => $category,'limit' => 3)
  );
}
else
{
  cq_dart_slot('300x250', 'collections', $category->getSlug(), 'sidebar');

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('category' => $category,'limit' => 3)
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
