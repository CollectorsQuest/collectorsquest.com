<?php
/**
 * @var  $sortby  array
 * @var  $types   array
 */
?>

<div class="well spacer-inner-8">
  <ul class="nav nav-list">

    <li class="nav-header">Sort by:</li>
    <?php
      foreach ($sortby as $key => $params)
      {
        if ($params['active'] === true)
        {
          echo '<li class="active"><a href="javascript:void(0)" rel="nofollow"><i class="icon-ok"></i>&nbsp;', $params['name'],'</a></li>';
        }
        else
        {
          echo '<li>', link_to($params['name'], $params['route'], array('rel' => 'nofollow')),'</li>';
        }
      }
    ?>

    <li class="nav-header">Filter by:</li>
    <?php
      foreach ($types as $key => $params)
      {
        $name = $params['name'];

        if ($params['count'] >= 0)
        {
          $name .= ' ('. $params['count'] .')';
        }

        if ($params['active'] === true)
        {
          echo '<li class="active"><a href="javascript:void(0)"><i class="icon-ok"></i>&nbsp;', $name,'</a></li>';
        }
        else
        {
          $link = link_to_if($params['count'] != 0, $name, $params['route']);
          echo '<li>', $link,'</li>';
        }
      }
    ?>
  </ul>
</div>

<?php include_component('_sidebar', 'widgetCollectiblesForSale', array('limit' => 3, 'fallback' => 'random')); ?>

<?php include_component('_sidebar', 'widgetCollections', array('limit' => 5, 'fallback' => 'random')); ?>

<?php
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      '@misc_guide_to_collecting'
    );
  }
?>

<?php include_component('_sidebar', 'widgetMagnifyVideos', array('limit' => 4)); ?>

<?php include_component('_sidebar', 'widgetBlogPosts', array('limit' => 4)); ?>
