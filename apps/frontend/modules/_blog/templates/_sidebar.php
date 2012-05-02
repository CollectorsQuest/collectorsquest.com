<?php
/**
 * @var $wp_post wpPost
 * @var $data array
 */

if ($data['is_single'])
{
  echo '<!-- Blog Sidebar //-->';

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('wp_post' => $wp_post, 'limit' => 3)
  );
}
else if ($data['is_page'])
{
  echo '<!-- Blog Sidebar //-->';
}
else
{
  echo '<!-- Blog Sidebar //-->';
}
