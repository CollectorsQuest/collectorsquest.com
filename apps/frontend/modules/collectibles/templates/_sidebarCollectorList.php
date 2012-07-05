<?php
/**
 * @var $collector Collector
 */
?>

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
          'name' => 'Normal Collectibles',
          'uri' => array(
              'sf_route' => 'collectibles_by_collector',
              'sf_subject' => $collector
          ),
      ),
      'for_sale' => array(
          'name' => 'Collectibles for Sale',
          'uri' => array(
              'sf_route' => 'collectibles_for_sale_by_collector',
              'sf_subject' => $collector
          ),
      ),
    )); ?>
  </ul>
</div>
