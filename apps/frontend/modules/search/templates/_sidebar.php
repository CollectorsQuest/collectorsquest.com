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
        if (empty($params['route']) || empty($params['name']))
        {
          continue;
        }

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
