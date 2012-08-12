<?php
/**
 * @var $wp_post wpPost
 * @var $wp_user wpUser
 * @var $data array
 */

if ($data['is_single'])
{
  echo '<!-- Blog Sidebar Widget1 //-->';

  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('banners/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      '@misc_guide_to_collecting'
    );
  }

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('wp_post' => $wp_post, 'limit' => 3)
  );

  echo '<!-- Blog Sidebar Widget2 //-->';
  echo '<!-- Blog Sidebar Widget3 //-->';
  echo '<!-- Blog Sidebar Widget4 //-->';
  echo '<!-- Blog Sidebar Widget5 //-->';
  echo '<!-- Blog Sidebar Widget6 //-->';
  echo '<!-- Blog Sidebar Widget7 //-->';
  echo '<!-- Blog Sidebar Widget8 //-->';
  echo '<!-- Blog Sidebar Widget9 //-->';
}
else if ($data['is_page'])
{
  echo '<!-- Blog Sidebar Widget1 //-->';
  echo '<!-- Blog Sidebar Widget2 //-->';
  echo '<!-- Blog Sidebar Widget3 //-->';
  echo '<!-- Blog Sidebar Widget4 //-->';
  echo '<!-- Blog Sidebar Widget5 //-->';
  echo '<!-- Blog Sidebar Widget6 //-->';
  echo '<!-- Blog Sidebar Widget7 //-->';
  echo '<!-- Blog Sidebar Widget8 //-->';
  echo '<!-- Blog Sidebar Widget9 //-->';
}
else if ($data['is_author'])
{
  echo '<!-- Blog Sidebar Widget1 //-->';

  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('banners/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      '@misc_guide_to_collecting'
    );
  }

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('wp_user' => $wp_user, 'limit' => 3)
  );

  echo '<!-- Blog Sidebar Widget2 //-->';
  echo '<!-- Blog Sidebar Widget3 //-->';
  echo '<!-- Blog Sidebar Widget4 //-->';
  echo '<!-- Blog Sidebar Widget5 //-->';
  echo '<!-- Blog Sidebar Widget6 //-->';
  echo '<!-- Blog Sidebar Widget7 //-->';
  echo '<!-- Blog Sidebar Widget8 //-->';
  echo '<!-- Blog Sidebar Widget9 //-->';
}
else
{
  echo '<!-- Blog Sidebar Widget1 //-->';

  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('banners/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      '@misc_guide_to_collecting'
    );
  }

  echo '<!-- Blog Sidebar Widget2 //-->';
  echo '<!-- Blog Sidebar Widget3 //-->';
  echo '<!-- Blog Sidebar Widget4 //-->';
  echo '<!-- Blog Sidebar Widget5 //-->';
  echo '<!-- Blog Sidebar Widget6 //-->';
  echo '<!-- Blog Sidebar Widget7 //-->';
  echo '<!-- Blog Sidebar Widget8 //-->';
  echo '<!-- Blog Sidebar Widget9 //-->';
}
