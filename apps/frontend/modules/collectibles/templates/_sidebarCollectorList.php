<?php
/**
 * @var $collector Collector
 */
?>

<?php
  /* commented out so there is now way for users to reach legacy route @collectibles_by_collector
<div class="well" style="padding: 8px 0;">
  <ul class="nav nav-list">

    <li class="nav-header">Display:</li>
    <?php
    sfConfig::set('app_smart_menus_collectibles_for_collector_list', array(
        'template' => array(
            'normal' => '<li><a href="%url%">%name%</a></li>',
            'active' => '<li class="active"><a href="%url%"><i class="icon-ok"></i>&nbsp;%name%</a></li>',
        ),
    ));
    echo SmartMenu::generate('collectibles_for_collector_list', array(
      'normal' => array(
          'name' => 'Collectibles',
          'uri' => array(
              'sf_route' => 'collectibles_by_collector',
              'sf_subject' => $collector
          ),
      ),
      'for_sale' => array(
          'name' => 'Items for Sale',
          'uri' => array(
              'sf_route' => 'collectibles_for_sale_by_collector',
              'sf_subject' => $collector
          ),
      ),
    )); ?>
  </ul>
</div>
*/ ?>

<?php include_component('_sidebar', 'widgetMagnifyVideos', array('limit' => 4)); ?>

<?php include_component('_sidebar', 'widgetBlogPosts', array('limit' => 4)); ?>

<?php include_component('_sidebar', 'widgetCollectiblesForSale', array('limit' => 4, 'fallback' => 'random')); ?>

<?php include_component('_sidebar', 'widgetCollections', array('limit' => 5, 'fallback' => 'random')); ?>

