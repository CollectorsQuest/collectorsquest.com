<?php
/**
 * @var  $sortBy  array
 * @var  $types   array
 */
?>

<div class="well spacer-inner-8">
  <ul class="nav nav-list">

    <li class="nav-header">Sort by:</li>
    <?php
    foreach ($sortBy as $key => $params)
    {
      if ($params['active'] === true)
      {
        echo '<li class="active"><a href="javascript:void(0)" rel="nofollow"><i class="icon-ok"></i>&nbsp;', $params['name'], '</a></li>';
      }
      else
      {
        echo '<li>', link_to($params['name'], $params['route'], array('rel' => 'nofollow')), '</li>';
      }
    }
    ?>
  </ul>
</div>
