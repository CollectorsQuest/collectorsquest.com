<?php
/**
 * @var $sf_user  cqFrontendUser
 * @var $height stdClass
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
?>

<?php
  if (isset($category) && $category instanceof ContentCategory)
  {
    include_component(
      '_sidebar', 'widgetMarketplaceCategories',
      array('current_category' => $category, 'height' => &$height)
    );
  }
?>

<?php include_component('_sidebar', 'widgetMarketplaceExplore', array('height' => &$height)); ?>
