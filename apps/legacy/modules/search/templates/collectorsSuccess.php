<br clear="all"/>

<?php if ($collectors): ?>
<div id="search-collectors">
  <?php
    foreach ($collectors as $i => $collector)
    {
      // Show the collection (in grid, list or hybrid view)
      include_partial(
        'collectors/grid_view_collector',
        array(
          'collector' => $collector,
          'culture' => $sf_user->getCulture(),
          'i' => $i
        )
      );

      echo (($i + 1) % 2 == 0) ? '<br clear="all">' : null;
    }
  ?>
<br clear="all"/>
<?php endif; ?>

<?php
  include_partial(
    'global/pager',
    array('pager' => $pager, 'options' => array('url' => '@search_collectors?q='. $q))
  );
?>
