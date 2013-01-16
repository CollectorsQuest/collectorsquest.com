<?php
/*
/* @var $wp_post wpPost            */
/* @var $wp_user wpUser            */
/* @var $data    array             */
/* @var $sf_user cqFrontendUser    */
/* @var $sf_request cqWebRequest   */

if ($data['is_single'])
{
  echo '<!-- Blog Sidebar Widget1 //-->';

  if (!$sf_user->isAuthenticated())
  {
    if ($sf_request->isMobileLayout())
    {
      echo link_to(
        cq_image_tag(
          'headlines/2012-06-24_CQGuidePromo_635x111.png',
          array('class' => 'spacer-top-20')
        ),
        'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
      );
    }
    else
    {
      echo link_to(
        cq_image_tag(
          'headlines/2012-06-24_CQGuidePromo_300x90.png',
          array('class' => 'spacer-top-20 mobile-optimized-300 center')
        ),
        'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
      );
    }
  }

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('wp_post' => $wp_post, 'limit' => 4)
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
    if ($sf_request->isMobileLayout())
    {
      echo link_to(
        cq_image_tag(
          'headlines/2012-06-24_CQGuidePromo_635x111.png',
          array('class' => 'spacer-top-20')
        ),
        'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
      );
    }
    else
    {
      echo link_to(
        cq_image_tag(
          'headlines/2012-06-24_CQGuidePromo_300x90.png',
          array('class' => 'spacer-top-20 mobile-optimized-300 center')
        ),
        'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
      );
    }
  }

  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array('wp_user' => $wp_user, 'limit' => 4)
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
  if (!isset($data['is_404']))
  {
    echo '<!-- Blog Sidebar Widget1 //-->';
  }

  if (!$sf_user->isAuthenticated())
  {
    if ($sf_request->isMobileLayout())
    {
      echo link_to(
        cq_image_tag(
          'headlines/2012-06-24_CQGuidePromo_635x111.png',
          array('class' => 'spacer-top-20')
        ),
        'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
      );
    }
    else
    {
      echo link_to(
        cq_image_tag(
          'headlines/2012-06-24_CQGuidePromo_300x90.png',
          array('class' => 'spacer-top-20 mobile-optimized-300 center')
        ),
        'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
      );
    }
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
