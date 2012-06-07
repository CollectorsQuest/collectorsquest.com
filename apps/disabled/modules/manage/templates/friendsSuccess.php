<br clear="all"><br>

<?php
  foreach ($friends as $i => $collector)
  {
    // Show the collector (in grid, list or hybrid view)
    include_partial(
      'collectors/grid_view_collector',
      array('collector' => $collector, 'culture' => $sf_user->getCulture(), 'i' => $i)
    );

    echo (($i+1)%2==0) ? '<br clear="all">' : '';
  }
?>
